<?php
    include("../database/database.php");


    // Get user count
$userCountQuery = "SELECT COUNT(*) AS user_count FROM users";
$userCountResult = $connection->query($userCountQuery);
$userCountRow = mysqli_fetch_assoc($userCountResult);
$userCount = $userCountRow['user_count'] ? $userCountRow['user_count'] : 0;


?>