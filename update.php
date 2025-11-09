 
 <?php
 $conn = new mysqli('localhost','root','','tool_library');
 $conn=$name = $category = $description = $condition = "";
        $edit_id=null;

        if (isset($_POST['update'])) {
            $edit_id = $_POST['edit_id'];
                $name = $_POST['name'];
                $category = $_POST['category'];
                $description = $_POST['description'];
                $condition = $_POST['condition'];
                $query = mysqli_query($conn, "UPDATE tools SET name='$name', category='$category', description='$description', `condition`='$condition' WHERE id='$edit_id'");
                mysqli_query($conn, $query);
        
        header("Location: tools.php");
        }
    ?>
   <!DOCTYPE html>
   <html lang="en">
   <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update</title>
   </head>
   <body>
     <form action="tools.php" method="POST">
        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
        <input type="text" name="name" value="<?php echo $name; ?>" placeholder="Tool Name"><br><br>
        <input type="text" name="category" value="<?php echo $category; ?>" placeholder="Tool Category"><br><br>
        <input type="text" name="description" value="<?php echo $description; ?>" placeholder="Tool Description"><br><br>
        <select name="condition">
            <option value="new" <?php if ($condition == 'new') echo 'selected'; ?>>New</option>
            <option value="good" <?php if ($condition == 'good') echo 'selected'; ?>>Good</option>
            <option value="fair" <?php if ($condition == 'fair') echo 'selected'; ?>>Fair</option>
            <option value="poor" <?php if ($condition == 'poor') echo 'selected'; ?>>Poor</option>  
        </select><br><br>
        <input type="submit" name="update_tool" value="Update Tool">
  <button href="tools.php">HOME</button>
    </form>
   </body>
   </html>