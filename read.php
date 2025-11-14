<?php
// read.php
require_once __DIR__ . '/auth.php'; // load auth.php and $mysqli

// Login & session check
try_remember_login();
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}
enforce_session_timeout(1800); // optional: 30 min timeout
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tools</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
            background-color: #f0f8ff;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px 12px;
            text-align: center;
        }
        th {
            background-color: #4868f3;
            color: white;
        }
        a {
            color: red;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        form {
            text-align: center;
            margin: 10px;
        }
        button {
            padding: 6px 12px;
            margin: 5px;
        }
    </style>
</head>
<body>

<form action="" method="POST">
    <button type="submit" name="view">View Tools</button>
</form>

<form action="tools.php">
    <button type="submit">Home</button>
</form>

<?php
if (isset($_POST['view'])) {
    // Fetch all tools
    $result = $mysqli->query("SELECT * FROM tools");

    if ($result && $result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Condition</th>
                    <th>Delete</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['category']) . "</td>";
            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
            echo "<td>" . htmlspecialchars($row['condition']) . "</td>";
            echo "<td><a href='delete.php?id=" . urlencode($row['id']) . "' onclick=\"return confirm('Are you sure you want to delete this tool?');\">Delete</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='text-align:center;'>No tools found.</p>";
    }
}
?>

</body>
</html>