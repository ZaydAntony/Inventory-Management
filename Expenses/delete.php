<?php
include("../database/database.php");
session_start(); // Start the session to access session variables

// Check if an ID is provided for deletion
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the delete query
    $sql = "DELETE FROM expenses WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Successfully deleted the expense

        // Recalculate total expenses
        $totalQuery = "SELECT SUM(total) AS total_expenses FROM expenses";
        $totalResult = $connection->query($totalQuery);
        $totalRow = mysqli_fetch_assoc($totalResult);
        $_SESSION['totalExpenses'] = $totalRow['total_expenses'] ? $totalRow['total_expenses'] : 0;

        // Redirect with a success message
        header("Location: ../Expenses/expenses.php?alert=success");
        exit();
    } else {
        // Set an error message in session
        $_SESSION['error_message'] = 'Failed to delete expense.';
    }

    // Close the statement
    $stmt->close();
} else {
    // Handle the case where no ID is provided
    $_SESSION['error_message'] = 'No ID provided for deletion.';
}

// Redirect back to the expenses page in case of error
header("Location: ../Expenses/expenses.php");
exit();
?>