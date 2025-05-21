<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../CSS/admin_login.css">
</head>
<body>

    <div class="container">
        <h2>Admin Login</h2>
        <form action="admin_auth.php" method="POST">
            <input type="text" name="username" placeholder="Enter Admin Username" required>
            <input type="password" name="password" placeholder="Enter Admin Password" required>
            <button type="submit" class="btn btn-admin">Login</button>
            <a href="register_admin.php">Register</a>
            </form>
    </div>

</body>
</html>
