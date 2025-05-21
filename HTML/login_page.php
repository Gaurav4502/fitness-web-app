<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "fitness_app");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve and sanitize inputs
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validation
    if (empty($email) || empty($password)) {
        die("Email and password are required.");
    }

    // SQL Select
    $sql = "SELECT id, password_hash FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $password_hash);
        $stmt->fetch();
        
        if (password_verify($password, $password_hash)) {
            // Set session correctly
            $_SESSION['user_id'] = $user_id;

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password.'); window.location.href='login_page.php';</script>";
        }
    } else {
        echo "<script>alert('No user found with this email.'); window.location.href='login_page.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login to the Fitness Club to access your dashboard and track fitness goals.">
    <meta name="author" content="Your Name">
    <meta name="keywords" content="fitness, login, health, workout, gym">
    <title>Fitness Club - Login</title>
    <link rel="stylesheet" href="../CSS/login_page.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <!-- Logo Section -->
            <div class="logo">
                <img src="../IMAGES/fitness_web_application_logo.png" alt="Fitness Club Logo">
            </div>

            <h1>Welcome Back</h1>
            <p>Log in to continue your fitness journey.</p>

            <!-- Login Form -->
            <form action="" method="POST" id="loginForm" aria-labelledby="loginFormTitle">
                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" aria-label="Email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Enter your email" 
                        required 
                        aria-required="true"
                        aria-describedby="emailHelp"
                    >
                    <small id="emailHelp" class="form-text">Enter the email associated with your account.</small>
                </div>
                
                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" aria-label="Password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your password" 
                        required 
                        aria-required="true"
                        aria-describedby="passwordHelp"
                    >
                    <small id="passwordHelp" class="form-text">Enter your password to log in.</small>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" aria-label="Log In">Log In</button>
                
                <!-- Create Account Link -->
                <p class="create-account">
                    Don't have an account? <a href="../HTML/register.php" aria-label="Sign Up">Sign Up</a>
                </p>
            </form>
            
            <!-- Active Users Section -->
            <div id="active-users" aria-live="polite">
                <p>Active Users: <span id="user-count">Loading...</span></p>
            </div>
        </div>
    </div>
    
    <script>
        async function fetchActiveUsers() {
            try {
                const response = await fetch('/userCount');
                if (response.ok) {
                    const data = await response.json();
                    document.getElementById('user-count').innerText = data.activeUsers;
                } else {
                    document.getElementById('user-count').innerText = 'Error';
                }
            } catch (error) {
                console.error('Failed to fetch active users:', error);
                document.getElementById('user-count').innerText = 'Error';
            }
        }
    
        setInterval(fetchActiveUsers, 5000); // Update active users every 5 seconds
        fetchActiveUsers(); // Initial fetch
    </script>
    
</body>
</html>