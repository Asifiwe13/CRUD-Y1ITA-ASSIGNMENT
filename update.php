<?php
// update.php
require_once __DIR__ . '/auth.php'; // load auth.php and $mysqli

// Login & session check
try_remember_login();
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}
enforce_session_timeout(1800); // optional: 30 min timeout

// Initialize variables
$name = $category = $description = $condition = "";
$edit_id = null;

// Get tool ID to edit
if (isset($_GET['id'])) {
    $edit_id = (int)$_GET['id'];
    $stmt = $mysqli->prepare("SELECT * FROM tools WHERE id = ?");
    $stmt->bind_param('i', $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $category = $row['category'];
        $description = $row['description'];
        $condition = $row['condition'];
    } else {
        // Tool not found
        header("Location: read.php");
        exit;
    }
    $stmt->close();
}

// Handle form submission
if (isset($_POST['update_tool']) && isset($_POST['edit_id'])) {
    $edit_id = (int)$_POST['edit_id'];
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $condition = $_POST['condition'];

    $stmt = $mysqli->prepare("UPDATE tools SET name = ?, category = ?, description = ?, `condition` = ? WHERE id = ?");
    $stmt->bind_param('ssssi', $name, $category, $description, $condition, $edit_id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: read.php");
        exit;
    } else {
        $stmt->close();
        die("Error updating tool: " . $mysqli->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Tool</title>
    <style>
        body {
            text-align: center;
            background-color: #f0f8ff;
        }
        form {
            display: inline-block;
            margin-top: 50px;
            background-color: #4868f3;
            padding: 20px;
            border-radius: 10px;
            color: white;
        }
        input, select, button {
            margin: 8px 0;
            padding: 6px;
        }
    </style>
</head>
<body>

<h2>Update Tool</h2>

<form action="" method="POST">
    <input type="hidden" name="edit_id" value="<?= htmlspecialchars($edit_id) ?>">
    <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" placeholder="Tool Name" required><br>
    <input type="text" name="category" value="<?= htmlspecialchars($category) ?>" placeholder="Tool Category" required><br>
    <input type="text" name="description" value="<?= htmlspecialchars($description) ?>" placeholder="Tool Description" required><br>
    <select name="condition" required>
        <option value="new" <?= $condition === 'new' ? 'selected' : '' ?>>New</option>
        <option value="good" <?= $condition === 'good' ? 'selected' : '' ?>>Good</option>
        <option value="fair" <?= $condition === 'fair' ? 'selected' : '' ?>>Fair</option>
        <option value="poor" <?= $condition === 'poor' ? 'selected' : '' ?>>Poor</option>
    </select><br>
    <input type="submit" name="update_tool" value="Update Tool"><br>
    <button type="button" onclick="window.location.href='tools.php'">Home</button>
</form>

</body>
</html>
