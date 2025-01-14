<?php
    include("../database/database.php");

    session_start();

        $totalExpenses = isset($_SESSION['totalExpenses']) ? $_SESSION['totalExpenses'] : 0;

// Retrieve total sales from session
        $totalsales = isset($_SESSION['totalsales']) ? $_SESSION['totalsales'] : 0;

        // Get user count
        $userCountQuery = "SELECT COUNT(*) AS user_count FROM users";
        $userCountResult = $connection->query($userCountQuery);
        $userCountRow = mysqli_fetch_assoc($userCountResult);
        $userCount = $userCountRow['user_count'] ? $userCountRow['user_count'] : 0;

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/dashboard.css"/>
</head>
<body>
        <div class="d-flex">
            <div class="side">
                <?php include("../navigation/sidebar.php"); ?>
            </div>
            <div class="container-fluid" style="margin-left: 300px;">
            <div class="header">
                        <h1>System Reports</h1>
                    </div>
                    <div class="cards d-flex flex-wrap justify-content-between">
                        <div class="text-center card" id="expense">
                            <h3>Expenses</h3><br>
                            <p><strong>Total</strong> Ksh. <?php echo number_format($totalExpenses, 2); ?></p>
                        </div>

                        <div class="text-center card" id="sales">
                            <h3>Sales</h3><br>
                            <p><strong>Total</strong> Ksh. <?php echo number_format($totalsales, 2); ?></p>
                        </div>

                        <div class="text-center card" id="profits">
                            <h3>Inventory Value</h3><br>
                            <p><strong>Total: </strong><?php echo $totalPurchasePrice ?></p>
                        </div>

                        <div class="text-center card" id="users">
                            <h3>Users</h3>
                            <p><strong><?php echo $userCount ?> </strong></p><br>
                        </div>
                    </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>




