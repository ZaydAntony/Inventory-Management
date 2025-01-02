<?php
include("../database/database.php");

$id = $_GET['id'];
$sql = "DELETE FROM storeproducts WHERE id = $id";
$result = mysqli_query($connection, $sql);

if ($result) {
    // Redirect with a simple success message
    header("Location: ../inventory/inventory.php?alert=success");
    exit();
} else {
    echo 'Error deleting record.';
}
?>