<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>read</title>
</head>
<body>
    <form action="" method="POST">
    <button type="submit" name="view">View tool or DELETE</button></form> <br><br>
    <form action="tools.php"><button type="submit" name="home">Home</button></form>
    <?php
        $conn=new mysqli('localhost','root','','tool_library');
        $query=mysqli_query($conn,"SELECT * FROM tools");

        if(isset($_POST['view'])){
            echo "<table border='1'>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Condition</th>
                        <th>Delete</th>
                    </tr>";
            while($row=mysqli_fetch_array($query)){
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['category']."</td>";
                echo "<td>".$row['description']."</td>";
                echo "<td>".$row['condition']."</td>";
                echo "<td><a href='delete.php?id=".$row['id']."'>Delete</a></td>";
             //   echo "<td><a href='update.php?id=".$row['id']."'>Update</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        }

    ?>
</body>
</html>