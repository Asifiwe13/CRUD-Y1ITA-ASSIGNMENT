<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tools_Library</title>
</head>
<style>
        #tool{
            width: 50%;
            margin-left: 25%;
            background-color: rgba(76, 104, 243, 1); 
            border-radius: 10px;
            border-radius: 7%;
        }
        form{
           
            text-overflow: clip;
             text-align: center;
             align-items: center;
            
        }
        body{
            background-color: rgba(85, 175, 244, 1);
        }
        h1{
            text-align: center;
        }
     </style>
<body>
    
  
    <h1>Tools_Library Form</h1>
   <fieldset id="tool"><form action="" method="POST" > 
    Tool Name:       <input type="text" name="name" placeholder="Tool Name"><br><br>
    Tool Category:   <input type="text" name="category" placeholder="Tool Category"><br><br>
    Tool Description:<input type="text" name="description" placeholder="Tool Description"><br><br>
    condition:  <select name="condition">
                    <option value="new">New</option>
                    <option value="good">Good</option>
                    <option value="fair">Fair</option>
                    <option value="poor">Poor</option>  
                    </select><br><br>
        <input type="submit" name="submit" value="Add Tool">
    </form><br>
        <form action="read.php"> <button type="submit" name="view">View tool</button></form> <br>
        <form action="delete.php"> <button type="submit" name="Delete">DELETE</button></form> <br>
        <form action="update.php"> <button type="submit" name="update">UPDATE</button></form>
    </fieldset> 
    <?php 
    if(isset($_POST['submit'])){    
        $name=$_POST['name'];
        $category=$_POST['category'];
        $description=$_POST['description'];
        $condition=$_POST['condition'];

        $conn=new mysqli('localhost','root','','tool_library');
        $query=mysqli_query($conn,"INSERT INTO tools(name,category,description,`condition`) VALUES('$name','$category','$description','$condition')");

        if($query){
            echo "Tool added successfully!";
        } else {
            echo "Error adding tool: " . mysqli_error($conn);
        }
        
    }
    
?>
</body>
</html>