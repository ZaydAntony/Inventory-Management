<?php
include("../database/database.php");
session_start();

// Check if the ID is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the existing sale data
    $query = "SELECT * FROM sales WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $sale = $result->fetch_assoc();

    // Check if the sale exists
    if (!$sale) {
        echo "Sale not found.";
        exit;
    }
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST["name"];
    $category = $_POST["category"];
    $type = $_POST["type"];
    $quantity = $_POST["quantity"];
    $price = $_POST["price"];
    $payment_mode = $_POST["payment_mode"];
    $product_id = $_POST["product_id"];

    // Calculate total based on quantity and price
    $total = $quantity * $price;

    // Update the sale in the database
    $updateQuery = "UPDATE sales SET name = ?, category = ?, type = ?, quantity = ?, price = ?, total = ?, payment_mode = ?, product_id = ? WHERE id = ?";
    $updateStmt = $connection->prepare($updateQuery);
    $updateStmt->bind_param("ssiiissii", $name, $category, $type, $quantity, $price, $total, $payment_mode, $product_id, $id);

    if ($updateStmt->execute()) {
        // Recalculate total sales
        $totalQuery = "SELECT SUM(total) AS total_sales FROM sales";
        $totalResult = $connection->query($totalQuery);
        $totalRow = mysqli_fetch_assoc($totalResult);
        $totalsales = $totalRow['total_sales'] ? $totalRow['total_sales'] : 0;

        $_SESSION['totalsales'] = $totalsales;

        // Redirect back to the sales page after successful update
        header("Location: sales.php?alert=success");
        exit;
    } else {
        echo "Error updating sale: " . $updateStmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/popup.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Edit Sale</title>
</head>
<body>
<div class="container">
    <h2>Edit Sale</h2>
    <form action="edit.php?id=<?php echo $id; ?>" method="POST">
        <div class="row">
            <div class="mb-3 col">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($sale['name']); ?>" required/>
            </div>
            <div class="mb-3 col">
                <label for="category" class="form-label">Category:</label>
                <input type="text" id="category" name="category" class="form-control" value="<?php echo htmlspecialchars($sale['category']); ?>" required/>
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col">
                <label for="type" class="form-label">Type:</label>
                <input type="text" id="type" name="type" class="form-control" value="<?php echo htmlspecialchars($sale['type']); ?>" required/>
            </div>
            <div class="mb-3 col">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" id="quantity" name="quantity" class="form-control" value="<?php echo htmlspecialchars($sale['quantity']); ?>" required oninput="calculateTotal()"/>
            </div>
        </div>
        
        <div class="row">
            <div class="mb-3 col">
                <label for="price" class="form-label">Price:</label>
                <input type="number" id="price" name="price" class="form-control" value="<?php echo htmlspecialchars($sale['price']); ?>" required oninput="calculateTotal()"/>
            </div>
            <div class="mb-3 col">
                <label for="total" class="form-label">Total:</label>
                <input type="number" id="total" name="total" class="form-control" value="<?php echo htmlspecialchars($sale['total']); ?>" required readonly/>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="product_id" class="form-label">Product:</label>
            <select id="product_id" name="product_id" class="form-control" required>
                <option value="" disabled>Select a product</option>
                <?php
                // Fetch products from the database
                $productQuery = "SELECT id, name FROM storeproducts"; 
                $productResult = mysqli_query($connection, $productQuery);

                while ($productRow = mysqli_fetch_assoc($productResult)) {
                    $selected = ($productRow['id'] == $sale['product_id']) ? 'selected' : '';
                    echo '<option value="' . $productRow['id'] . '" ' . $selected . '>' . $productRow['name'] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Payment Mode:</label>
            <div>
                <label for="cash">Cash:</label>
                <input type="radio" id="cash" name="payment_mode" value="cash" <?php echo ($sale['payment_mode'] == 'cash') ? 'checked' : ''; ?> required>
                <label for="mpesa">M-Pesa:</label>
                <input type="radio" id="mpesa" name="payment_mode" value="mpesa" <?php echo ($sale['payment_mode'] == 'mpesa') ? 'checked' : ''; ?> required>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Update Sale</button>
        <a href="sales.php" class="btn btn-danger">Cancel</a>
    </form>
</div>

<script>
function calculateTotal() {
    const quantity = document.getElementById('quantity').value;
    const price = document.getElementById('price').value;
    const total = quantity * price;
    document.getElementById('total').value = total.toFixed(2); // Update total with two decimal places
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>