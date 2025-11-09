<?php
    $conn=new mysqli('localhost','root','','tool_library');
    $id=$_GET['id'];
    $query=mysqli_query($conn,"DELETE FROM tools WHERE id='$id'");
    header("Location: read.php");
?>
