<?php
// delete.php
require_once __DIR__ . '/auth.php'; // load auth.php and $mysqli

// Login & session check
try_remember_login();
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}
enforce_session_timeout(1800); // optional: 30 min timeout

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Use prepared statement to prevent SQL injection
    $stmt = $mysqli->prepare("DELETE FROM tools WHERE id = ?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: read.php");
        exit;
    } else {
        $stmt->close();
        die("Error deleting tool: " . $mysqli->error);
    }
} else {
    // No ID provided
    header("Location: read.php");
    exit;
}
?>
