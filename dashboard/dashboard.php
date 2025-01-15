<?php
include("../database/database.php");
include_once("../Reports/query.php");
include("./queries.php");
session_start(); // Start the session

// Retrieve total expenses from session
$totalExpenses = isset($_SESSION['totalExpenses']) ? $_SESSION['totalExpenses'] : 0;

// Retrieve total sales from session
$totalsales = isset($_SESSION['totalsales']) ? $_SESSION['totalsales'] : 0;


include_once("./queries.php");

$sql ="SELECT COUNT(*) AS count FROM active_users";
$result = mysqli_query($connection, $sql);
$row=mysqli_fetch_assoc($result);

$count=$row['count'];

// Get username from session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; // Default to 'Guest' if not set
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../styles/dashboard.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <div class="side">
            <?php include("../navigation/sidebar.php"); ?>
        </div>

        <div class="container-fluid" style="margin-left: 300px;"> <!-- Adjust margin to accommodate sidebar -->
            <div class="header">
                <h1>Dashboard</h1>
                <p>Welcome <strong><?php echo htmlspecialchars($username); ?></strong></p>
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
                    <p><strong>Total: </strong><?php echo isset($totalPurchasePrice) ? number_format($totalPurchasePrice, 2) : '0.00'; ?></p>
                </div>

                <div class="text-center card" id="users">
                    <h3>Users</h3>
                    <p><strong><?php echo $userCount ? : 0; ?></strong></p><br>
                    <h3>Active</h3><br/>
                    <p><?php echo $count; ?></p>
                </div>
            </div>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <table class="table table-striped table-hover text -center mt-4">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM users";
                        $result = $connection->query($sql);

                        if ($result && mysqli_num_rows($result) > 0) {
                            // Loop through all rows
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "
                                <tr>
                                    <td>" . htmlspecialchars($row['username']) . "</td>
                                    <td>" . htmlspecialchars($row['role']) . "</td>
                                    <td>" . htmlspecialchars($row['created_at']) . "</td>
                                    <td>
                                        <a href='./edit.php?id=" . $row['id'] . "' class='btn btn-success' style='margin-right:10px;'>Update</a>
                                        <a href='./delete.php?id=" . $row['id'] . "' class='btn btn-danger'>Delete</a>
                                    </td>
                                </tr>
                                ";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No users found</td></tr>";
                        }

                        mysqli_close($connection);
                        ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>