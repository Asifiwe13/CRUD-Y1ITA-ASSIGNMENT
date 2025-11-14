<?php
require_once __DIR__ . '/auth.php';
try_remember_login();
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}
enforce_session_timeout(1800);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Tools Library</title>
</head>
<body>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #9ddafdff;
            align-items: center;
            margin-left:35%;
            margin-top:10%;
        }
        fieldset{
           width: 60%;
        }
        button {
            background-color: #4868f3;
            color: white;
            padding: 10px 20px;
            border: 1px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
    </style>
<h1>Tools Library</h1>
<fieldset>
<button><a href="logout.php">Logout</a><br><br></button><br><br>

<form method="POST">
    Name: <input type="text" name="name"><br>
    Category: <input type="text" name="category"><br>
    Description: <input type="text" name="description"><br>
    Condition: <select name="condition">
        <option value="new">New</option>
        <option value="good">Good</option>
        <option value="fair">Fair</option>
        <option value="poor">Poor</option>
    </select><br><br>
    <button type="submit" name="add">Add Tool</button>
</form>

<?php
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $condition = $_POST['condition'];

    $stmt = $mysqli->prepare("INSERT INTO tools(name, category, description, `condition`) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $name, $category, $description, $condition);
    $stmt->execute();

    echo "Tool added successfully!";
}
?><br>
<button><br><a href="read.php">View Tools</a></button>
</fieldset>
</body>
</html>
