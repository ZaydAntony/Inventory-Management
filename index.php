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
        <input type="text" name="role"/><br>

        <label>Password:</label>
        <input type="password" name="password"/><br>

        <input type="submit" name="Register" Value="Register"/><br>
    </Form>
</body>
</html>

<?php


?>