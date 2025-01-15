<?php
    include_once("../database/database.php");

    $sql = "SELECT * FROM storeproducts";
    $result = $connection->query($sql);

    if ($result) {
        
        if (mysqli_num_rows($result) > 0) {
            $purchasePrices = []; // Initialize an array to hold purchase prices
            $totalPurchasePrice = 0; // Initialize a variable to hold the total purchase price
    
            while ($row = mysqli_fetch_assoc($result)) {
                $purchasePrice = $row['Purchase_price']; // Get the purchase price
                $purchasePrices[] = $purchasePrice; // Store each purchase price in the array
                $totalPurchasePrice += $purchasePrice;
            }}}
?>