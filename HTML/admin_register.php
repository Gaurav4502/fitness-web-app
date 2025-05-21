<?php
session_start();
include 'db_connect_admin.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate username (only letters, max 35 characters)
    if (!preg_match("/^[A-Za-z]{1,35}$/", $username)) {
        $error = "Username must be alphabetic and max 35 characters!";
    }
    // Validate email format
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    }
    // Validate password (at least 8 characters, 4 numbers, 1 special character)
    elseif (!preg_match("/^(?=.*\d{4,})(?=.*[\W]).{8,}$/", $password)) {
        $error = "Password must be at least 8 characters, include 4 numbers & 1 special character!";
    } else {
        // Secure password hashing
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Check if username or email already exists
        $check_sql = "SELECT id FROM admins WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username or Email already exists!";
        } else {
            // Insert new admin
            $sql = "INSERT INTO admins (username, email, password_hash) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                echo "<script>alert('Admin registered successfully!'); window.location.href='admin_login.php';</script>";
                exit();
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="../CSS/admin_register.css"> <!-- External CSS file -->
</head>
<body>
    <div class="container">
        <h2 class="title">Admin Registration</h2> <!-- Ensuring title is above and centered -->
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form action="" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required maxlength="20"
                pattern="[A-Za-z]{1,20}" title="Only alphabets, max 20 characters"> <!-- Reduced maxlength -->

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required maxlength="50"> <!-- Reduced maxlength -->

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required minlength="8" maxlength="20"
                pattern="^(?=.*\d{4,})(?=.*[\W]).{8,}$"
                title="At least 8 characters, 4 numbers & 1 special character"> <!-- Reduced maxlength -->

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
