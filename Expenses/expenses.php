<?php
include("../database/database.php");
session_start();

// Check if the form was submitted
$formSubmitted = isset($_POST["expense"]) ? true : false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"> 
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../styles/popup.css"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> 
<link rel="preconnect" href="https://fonts.googleapis.com"> <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
<div class="d-flex">
        <div class="side">
            <?php include("../navigation/sidebar.php"); ?>
        </div>
        <div class="maincontent" style="margin-left: 250px; padding-left:10px; width: 100%;" >
            <div class="header">
                <div><h1>Expenses</h1></div>
                <a id="openPopupLink" class="btn btn-success">New Expense</a>
            </div>
            
            <div class="pop-container" id="popupContainer">
                <div class="popup-content">
                    <h2>Add Expense</h2>
                    <form id="popupForm" action="expenses.php" method="POST">
                        <div class="row">
                            <div class="col">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" id="name" name="name" class="form-control" autocomplete="off" required/>
                            </div>
                            <div class="col">
                                <label for="total" class="form-label">Total:</label>
                                <input type="number" id="totalInput" name="total" placeholder="Ksh.0.00" class="form-control" required/>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success" name="expense">Record Expense</button>
                        <button type="button" class="close-popup btn btn-dark">Close</button>
                    </form>
                </div>
            </div>

            <script>
                // Get elements
                const openPopupLink = document.getElementById('openPopupLink');
                const popupContainer = document.getElementById('popupContainer');
                const closePopupButtons = document.querySelectorAll('.close-popup');

                // Open popup
                openPopupLink.addEventListener('click', function (event) {
                    event.preventDefault();
                    popupContainer.style.display = 'flex'; // Show the popup
                });

                // Close popup
                closePopupButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        popupContainer.style.display = 'none'; // Hide the popup
                    });
                });

                // Click outside to close
                popupContainer.addEventListener('click', function (event) {
                    if (event.target === popupContainer) {
                        popupContainer.style.display = 'none'; // Hide the popup
                    }
                });

                // Prevent popup from showing on refresh
                window.onload = function() {
                    <?php if ($formSubmitted) { ?>
                        popupContainer.style.display = 'none'; // Ensure popup is hidden if form was submitted
                    <?php } ?>
                };
            </script>
        

        <table class="table table-striped-column table-hover text-center">
            <thead class="table-dark">
                <tr>
                    
                    <th>Name</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query= "SELECT * FROM expenses";
                $result = $connection->query($query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "
                        <tr>
                            
                            <td>{$row['name']}</td>
                            <td>Ksh.{$row['total']}</td>
                            <td>{$row['date']}</td>
                            <td>
                                <a href='edit.php?id={$row['id']}' class='btn btn-success' style='margin-right:60px;'>Update</a>
                                <a href='delete.php?id={$row['id']}' class='btn btn-danger'>Delete</a>
                            </td>
                        </tr>
                    ";
                    }
                } else {
                    echo "<tr><td colspan='5'>No Expenses found</td></tr>";
                }
                if ($formSubmitted) {
                    $name = $_POST["name"];
                    $total = $_POST["total"];
                    $sql = "INSERT INTO expenses(name, total) VALUES('$name','$total')";
                    try {
                        mysqli_query($connection, $sql);
                        
                        // Update total expenses in session
                        $totalQuery = "SELECT SUM(total) AS total_expenses FROM expenses";
                        $totalResult = $connection->query($totalQuery);
                        $totalRow = mysqli_fetch_assoc($totalResult);
                        $_SESSION['totalExpenses'] = $totalRow['total_expenses'] ? $totalRow['total_expenses'] : 0;
                
                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> New expense added.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                    } catch (Exception $e) {
                        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Failed!</strong> ' . $e->getMessage() . '
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                    }
                }
                
                // Fetch total expenses for display
                if (!isset($_SESSION['totalExpenses'])) {
                    $totalQuery = "SELECT SUM(total) AS total_expenses FROM expenses";
                    $totalResult = $connection->query($totalQuery);
                    $totalRow = mysqli_fetch_assoc($totalResult);
                    $_SESSION['totalExpenses'] = $totalRow['total_expenses'] ? $totalRow['total_expenses'] : 0;
                }
                
                

                mysqli_close($connection);
                ?>
            </tbody>
            </table>

            <div class="total-expenses">
                <h3>Total Expenses: Ksh. <?php echo number_format($_SESSION['totalExpenses'], 2); ?></h3>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>