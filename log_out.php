<?php

    include("./database/database.php");
    session_start();

    $userId=$_SESSION['user_id'];
    $connection->query("DELETE FROM active_users WHERE user_id = $userId");

    session_unset();
    session_destroy();


    header("Location:./new_login/login.php");
    exit();
?>