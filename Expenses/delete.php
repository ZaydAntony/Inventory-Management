<?php
include("../database/database.php");

$id = $_GET['id'];
$sql = "DELETE FROM expenses WHERE id = $id";
$result = mysqli_query($connection, $sql);

if ($result) {
    // Redirect with a simple success message
    header("Location: ../Expenses/expenses.php?alert=success");
    exit();
} else {
    echo 'Error deleting record.';
}
?>