<?php
include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/popup.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
<div class="maincontent">
    <div class="header">
        <div><h1>Sales</h1></div>
        <a id="openPopupLink"  class="btn btn-success">New Sale</a>
    </div>
    
    <div class="pop-container" id="popupContainer">
        <div class="popup-content">
            <h2>Add Sale</h2>
            <form id="popupForm" action="sales.php" method="POST">
                <div class="row">
                    <div class="col">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" autocomplete="off" required/>
                    </div>

                    <div class="col">
                        <label for="category" class="form-label">Category:</label>
                        <input type="text" id="category" name="category" class="form-control" autocomplete="off" required/>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label for="type" class="form-label">Type:</label>
                        <input type="text" id="type" name="type" class="form-control" autocomplete="off" required/>
                    </div>

                    <div class="col">
                    <label for="quantity" class="form-label">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" class="form-control" autocomplete="off" required/>
                    </div>
                </div>
    
                <label for="price" class="form-label">Price</label>
                <select id="price" name="price" class="form-control" required>
                    <option value="" disabled selected>Select a price</option>
                    <?php
                    // Fetch unique prices from the database
                    $query = "SELECT DISTINCT Wholesale_price, Retail_price FROM storeproducts";
                    $result = mysqli_query($connection, $query);
                    $prices = [];

                    while ($row = mysqli_fetch_assoc($result)) {
                        if (!in_array($row['Wholesale_price'], $prices)) {
                            $prices[] = $row['Wholesale_price'];
                            echo '<option value="' . $row['Wholesale_price'] . '">' . $row['Wholesale_price'] . '</option>';
                        }
                        if (!in_array($row['Retail_price'], $prices)) {
                            $prices[] = $row['Retail_price'];
                            echo '<option value="' . $row['Retail_price'] . '">' . $row['Retail_price'] . '</option>';
                        }
                    }
                    ?>
                </select>

                <label for="total" class="form-label">Total:</label>
                <input type="number" id="totalInput" name="total" value="0.00" class="form-control" readonly/>

                <label for="product_id" class="form-label">Product:</label>
                <select id="product_id" name="product_id" class="form-control" required>
                    <option value="" disabled selected>Select a product</option>
                    <?php
                    // Fetch products from the database
                    $productQuery = "SELECT id, name FROM storeproducts"; // Adjust the query as needed
                    $productResult = mysqli_query($connection, $productQuery);

                    while ($productRow = mysqli_fetch_assoc($productResult)) {
                        echo '<option value="' . $productRow['id'] . '">' . $productRow['name'] . '</option>';
                    }
                    ?>
                </select>
                    <div class="row">
                    <label class="form-label">Payment Mode:</label>
                            <div class="col">
                                <label for="cash">Cash:</label>
                                <input type="radio" id="cash" name="payment_mode" value="cash" required>
                            </div>
                            <div class="col">
                                <label for="mpesa">M-Pesa:</label>
                                <input type="radio" id="mpesa" name="payment_mode" value="mpesa" required>
                            </div>

                    </div>
                
                <button type="submit" class="btn btn-success" name="sale">Complete sale</button>
                <button type="button" class="close-popup btn btn-dark">Close</button>
            </form>
        </div>
    </div>

    <script>
        // Get elements
        const openPopupLink = document.getElementById('openPopupLink');
        const popupContainer = document.getElementById('popupContainer');
        const closePopupButtons = document.querySelectorAll('.close-popup');
        const priceSelect = document.getElementById('price');
        const quantityInput = document.getElementById('quantity');
        const totalInput = document.getElementById('totalInput');

        // Open popup
        openPopupLink.addEventListener('click', function (event) {
            event.preventDefault();
            popupContainer.style.display = 'flex';
        });

        // Close popup
        closePopupButtons.forEach(button => {
            button.addEventListener('click', function () {
                popupContainer.style.display = 'none';
            });
        });

        // Click outside to close
        popupContainer.addEventListener('click', function (event) {
            if (event.target === popupContainer) {
                popupContainer.style.display = 'none';
            }
        });

        function calculateTotal() {
            const price = parseFloat(priceSelect.value);
            const quantity = parseFloat(quantityInput.value);
            if (!isNaN(price) && !isNaN(quantity)) {
                const total = (price * quantity).toFixed(2);
                totalInput.value = total; // Set the total input value
            } else {
                totalInput.value = '0.00';
            }
        }

        priceSelect.addEventListener('change', calculateTotal);
        quantityInput.addEventListener('input', calculateTotal);
    </script>
</div>

<table class="table table-striped-column table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Type</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
            <th>Payment Mode</th>
            <th>Action</th>

        </tr>
    </thead>

    <tbody>
        <?php
        $sql = "SELECT * FROM sales";
        $result = $connection->query($sql);

        if (mysqli_num_rows($result) > 0) {
            // Loop through all rows
            while ($row = mysqli_fetch_assoc($result)) {
                echo "
                <tr>
                    <td>{$row['name']}</td>
                    <td>{$row['category']}</td>
                    <td>{$row['type']}</td>
                    <td>{$row['quantity']} kgs.</td>
                    <td>Ksh. {$row['price']}</td>
                    <td>Ksh. {$row['total']}</td>
                    <td>{$row['payment_mode']}</td>
                    <td><a href='delete.php?id={$row['id']} ' class='btn btn-danger'>Delete</a></td>
                    

                </tr>
                ";
            }
        } else {
            echo "<tr><td colspan='7'>No sales found</td></tr>";
        }

        // Get data from form and post to the database
        if (isset($_POST["sale"])) {
            $name = $_POST["name"];
            $category = $_POST["category"];
            $type = $_POST["type"];
            $quantity = $_POST["quantity"];
            $price = $_POST["price"];
            $total = $_POST["total"];
            $payment_mode = $_POST["payment_mode"];
            
            $product_id = $_POST["product_id"]; // Get the product_id from the form

            // Prepare the SQL statement
            $stmt = $connection->prepare("INSERT INTO sales (name, category, type, quantity, payment_mode, price, total, product_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiisss", $name, $category, $type, $quantity, $payment_mode, $price, $total, $product_id);

            // Execute the statement
            if ($stmt->execute()) {
                echo "<script>alert ('Sale was successful')</script>";
            } else {
                echo "<script>alert('Sale failed: " . $stmt->error . "')</script>";
            }

            $stmt->close();
        }
        ?>
    </tbody>
</table>
</body>
</html>