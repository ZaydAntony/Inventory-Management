<?php
include("../database/database.php");

// Check if the ID is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the existing expense data
    $query = "SELECT * FROM expenses WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $expense = $result->fetch_assoc();

    // Check if the expense exists
    if (!$expense) {
        echo "Expense not found.";
        exit;
    }
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $total = $_POST['total'];

    // Update the expense in the database
    $updateQuery = "UPDATE expenses SET name = ?, total = ? WHERE id = ?";
    $updateStmt = $connection->prepare($updateQuery);
    $updateStmt->bind_param("ssi", $name, $total, $id);

    if ($updateStmt->execute()) {
        // Redirect back to the expenses page after successful update
        header("Location: expenses.php");
        exit;
    } else {
        echo "Error updating expense.";
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
    <title>Edit Expense</title>
</head>
<body>
<div class="container">
    <h2>Edit Expense</h2>
    <form action="edit.php?id=<?php echo $id; ?>" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($expense['name']); ?>" required/>
        </div>
        <div class="mb-3">
            <label for="total" class="form-label">Total:</label>
            <input type="number" id="total" name="total" class="form-control" value="<?php echo htmlspecialchars($expense['total']); ?>" required/>
        </div>
        <button type="submit" class="btn btn-success">Update Expense</button>
        <a href="expenses.php" class="btn btn-danger">Cancel</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>