<?php
// db.php
$mysqli = new mysqli('localhost', 'root', '', 'tool_library');
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');
?>
