<?php

    include("../database/database.php");
    include("../navigation/nav.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/login.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-box">
        <div class="login-header">
            <header>Login</header>
        </div>
        <div class="input-form">
            <Form action="login.php" method ="post">
            <input type="text" name="username" placeholder="username" class="input-field" required autocomplete="off"/><br>
            <input type="password" name="password" placeholder="password" class="input-field" required autocomplete="off"/><br>

            <input type="submit" name="login" Value="Login" id="login"/><br>
            </Form>
        </div>
    
    </div>
</body>
</html>

<?php

    if (isset($_POST["login"])) {
        $username=trim($_POST["username"]);
        $password=$_POST["password"];

        $stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username); // "s" indicates the type is string
        $stmt->execute();
        $result = $stmt->get_result();

        if (mysqli_num_rows($result)>0) {
            $row=mysqli_fetch_assoc($result);// user data as an array
            $dbusername=$row["username"];
            $dbpassword=$row["password"];

            if(password_verify($password, $dbpassword) && $username==$dbusername){//verification
                echo "<script>alert('Login was successful')</script>";
                header("Location: ../dashboard/dashboard.php");
                        exit();

            }else{
                echo "<script>alert('invalid username/password')</script>";
            }
            
        }
    }
    ;

    mysqli_close($connection);
    



?>