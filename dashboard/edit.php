<?php
include("../database/database.php");
include("../navigation/nav.php");

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Fetch user data
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "<script>alert('User  not found'); window.location.href='dashboard.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid request'); window.location.href='dashboard.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="../styles/signup.css" rel="stylesheet"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>

    <div class="signup-box">
        <div class="signup-header">
            <header>Edit User</header>
        </div>
        <div class="input-form">
            <form action="edit.php?id=<?php echo $userId; ?>" method="post">
                <input type="text" name="username" placeholder="username" class="input-field" value="<?php echo htmlspecialchars($user['username']); ?>" required autocomplete="off"/><br>
                <input type="text" name="role" placeholder="admin or cashier" class="input-field" value="<?php echo htmlspecialchars($user['role']); ?>" required autocomplete="off"/><br>
                <input type="password" name="password" placeholder="new password (leave blank to keep current)" class="input-field" autocomplete="off"/><br>

                <input type="submit" name="Update" value="Update" id="register"/><br>
            </form>
        </div>
    </div>

</body>
</html>

<?php
if (isset($_POST["Update"])) {
    $username = $_POST["username"];
    $role = $_POST["role"];
    $password = $_POST["password"];

    // Prepare the SQL statement
    if (!empty($password)) {
        // If a new password is provided, hash it
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username = ?, role = ?, password = ? WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sssi", $username, $role, $hash, $userId);
    } else {
        // If no new password is provided, update only username and role
        $sql = "UPDATE users SET username = ?, role = ? WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssi", $username, $role, $userId);
    }

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('User  updated successfully'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Update failed');</script>";
    }

    $stmt->close();
}

mysqli_close($connection);
?>