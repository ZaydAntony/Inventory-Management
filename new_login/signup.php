<?php
include("../database/database.php");
include("../navigation/nav.php");

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-up</title>
    <link href="../styles/signup.css" rel="stylesheet"/>;
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>

    <div class="signup-box">
        <div class="signup-header">
            <header>Sign-up</header>
        </div>
        <div class="input-form">
            <Form action="signup.php" method ="post">
            <input type="text" name="username" placeholder="username" class="input-field" required autocomplete="off"/><br>
            <input type="text" name="role" placeholder="admin or cashier" class="input-field" required autocomplete="off"/><br>
            <input type="password" name="password" placeholder="password" class="input-field" required autocomplete="off"/><br>

            <input type="submit" name="Register" Value="Register" id="register"/><br>
            </Form>
        </div>
    
    </div>
    
</body>
</html>

<?php
    if(isset($_POST["Register"]) ){
        $username=$_POST["username"];
        $role=$_POST["role"];
        $password=$_POST["password"];

        $hash= password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users(username, password, role)
                    VALUES('$username','$hash','$role')";
        
        try{
            mysqli_query($connection,$sql);
            echo "<script>alert('Registration was successful')</script>";
            header('Location: ../new_login/login.php');
        }catch(exception){
            echo "<script>alert('Registration failed')</script>";
        }

    }

    mysqli_close($connection);

?>