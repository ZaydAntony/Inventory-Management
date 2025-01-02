<?php
include("../database/database.php");

// Check if the ID is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the existing product data
    $query = "SELECT * FROM storeproducts WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Check if the product exists
    if (!$product) {
        echo "Product not found.";
        exit;
    }
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $type = $_POST['type'];
    $quantity = $_POST['quantity'];
    $purchase = $_POST['purchase'];
    $wholesale = $_POST['wholesale'];
    $retail = $_POST['retail'];

    // Update the product in the database
    $updateQuery = "UPDATE storeproducts SET name = ?, category = ?, type = ?, quantity = ?, Purchase_price = ?, Wholesale_price = ?, Retail_price = ? WHERE id = ?";
    $updateStmt = $connection->prepare($updateQuery);
    $updateStmt->bind_param("sssiidii", $name, $category, $type, $quantity, $purchase, $wholesale, $retail, $id);

    if ($updateStmt->execute()) {
        // Redirect back to the inventory page after successful update
        header("Location: inventory.php");
        exit;
    } else {
        echo "Error updating product.";
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
    <title>Edit Product</title>
</head>
<body>
<div class="container">
    <h2>Edit Product</h2>
    <form action="editRecord.php?id=<?php echo $id; ?>" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required/>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category:</label>
            <input type="text" id="category" name="category" class="form-control" value="<?php echo htmlspecialchars($product['category']); ?>" required/>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type:</label>
            <input type="text" id="type" name="type" class="form-control" value="<?php echo htmlspecialchars($product['type']); ?>" required/>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity:</label>
            <input type="number" id="quantity" name="quantity" class="form-control" value="<?php echo htmlspecialchars($product['quantity']); ?>" required/>
        </div>
        <div class="mb-3">
            <label for="purchase" class="form-label">Purchase Price:</label>
            <input type="number" id="purchase" name="purchase" class="form-control" value="<?php echo htmlspecialchars($product['Purchase_price']); ?>" required/>
        </div>
        <div class="mb-3">
            <label for="wholesale" class="form-label">Wholesale Price:</label>
            <input type="number" id="wholesale" name="wholesale" class="form-control" value="<?php echo htmlspecialchars($product['Wholesale_price']); ?>" required/>
        </div>
        <div class="mb-3">
            <label for="retail" class="form-label">Retail Price:</label>
            <input type="number" id="retail" name="retail" class="form-control" value="<?php echo htmlspecialchars($product['Retail_price']); ?>" required/>
        </div>
        <button type="submit" class="btn btn-success">Update Product</button>
        <a href="inventory.php" class="btn btn-danger">Cancel</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>