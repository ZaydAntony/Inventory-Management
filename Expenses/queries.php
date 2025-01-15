<?php

include("../database/database.php");
    session_start();
    // Update total expenses in session
    $totalQuery = "SELECT SUM(total) AS total_expenses FROM expenses";
    $totalResult = $connection->query($totalQuery);
    $totalRow = mysqli_fetch_assoc($totalResult);
    $_SESSION['totalExpenses'] = $totalRow['total_expenses'] ? $totalRow['total_expenses'] : 0;



?>