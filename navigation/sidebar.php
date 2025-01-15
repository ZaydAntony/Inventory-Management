<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/sidebar.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="side-bar">
        <header><image src="../assets/icon.png" class="icon2"/>Ngifa wholesalers</header>
        <ul>
            <li><a href="../dashboard/dashboard.php"><image src="../assets/dashboard.png" alt="dashboard" class="icon"/>Dashboard</a></li>
            <li><a href="../sales/sales.php"><image src="../assets/sales.png" alt="sales" class="icon"/>Sales</a></li>
            <li><a href="../inventory/inventory.php"><image src="../assets/inventory.png" alt="inventory" class="icon"/>Inventory</a></li>

            <?php

            if(isset($_SESSION['role']) && $_SESSION['role']==='admin'){
                echo'
                <li><a href="../Reports/reports.php"><image src="../assets/reports.png" alt="reports" class="icon"/>Reports</a></li>';
            }
            ?>
            <li><a href="../Expenses/expenses.php"><image src="../assets/expenses.png" alt="expenses" class="icon"/>Expenses</a></li>
            <li><a href="../log_out.php"><image src="../assets/logout.png" alt="logout" class="icon"/>log-out</a></li>
        </ul>
    </div>
</body>
</html>