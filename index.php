<?php
include("database.php");
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sessions</title>
</head>
<body>
    <Form action="index.php" method ="post">
        <label>Username:</label>
        <input type="text" name="username"/><br>

        <label>Role:</label>
        <input type="text" name="role" placeholder="admin or cashier"/><br>

        <label>Password:</label>
        <input type="password" name="password"/><br>

        <input type="submit" name="Register" Value="Register"/><br>
    </Form>
</body>
</html>

<?php
    if(isset($_POST["Register"])){
        $username=$_POST["username"];
        $role=$_POST["role"];
        $password=$_POST["password"];

        $hash= password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users(username, password, role)
                    VALUES('$username','$hash','$role')";
        
        try{
            mysqli_query($connection,$sql);
            echo "Registration was successful";
        }catch(exception){
            echo "Registration failed";
        }

    }else{
        echo "Please insert all the fields";
    }

    mysqli_close($connection);

?>