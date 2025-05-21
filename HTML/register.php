<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "fitness_app");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve and sanitize inputs
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $gender = $_POST['gender'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mobile = htmlspecialchars(trim($_POST['mobile']));
    $dob = $_POST['dob'];
    $weight = floatval($_POST['weight']);
    $height = floatval($_POST['height']);
    $fitness_goals = htmlspecialchars(trim($_POST['fitness_goals']));
    $goal_type = $_POST['goal_type'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $dietary_preferences = $_POST['dietary_preferences'];
    $activity_level = $_POST['activity_level'];

    // Validation errors array
    $errors = [];
    
    // Check for duplicate mobile or email
    $check_sql = "SELECT id FROM users WHERE mobile = ? OR email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $mobile, $email);
    $check_stmt->execute();
    $check_stmt->store_result();
    if ($check_stmt->num_rows > 0) {
        $errors[] = "Mobile number or email already exists. Please use a different one.";
    }
    $check_stmt->close();

    if (!preg_match("/^[a-zA-Z ]{1,30}$/", $first_name) || !preg_match("/^[a-zA-Z ]{1,30}$/", $last_name)) {
        $errors[] = "First name and last name must contain only letters and be up to 30 characters.";
    }
    if (!preg_match("/^[6-9]\d{9}$/", $mobile)) {
        $errors[] = "Invalid mobile number. It must be a 10-digit Indian number.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if ($weight <= 0 || $height <= 0) {
        $errors[] = "Weight and height must be positive numbers.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // If there are errors, show alert and stop execution
    if (!empty($errors)) {
        echo "<script>alert('" . implode("\n", $errors) . "');</script>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // SQL Insert with activity_level and dietary_preferences
        $sql = "INSERT INTO users (first_name, last_name, gender, email, mobile, dob, weight, height, fitness_goals, goal_type, dietary_preferences, activity_level, password_hash, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssddsssss", $first_name, $last_name, $gender, $email, $mobile, $dob, $weight, $height, $fitness_goals, $goal_type, $dietary_preferences, $activity_level, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! Redirecting to login page...'); window.location.href='login_page.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="../CSS/register.css">
</head>
<body>
    <div class="register-container">
        <h2>Register page</h2>
        <form action="register.php" method="POST" enctype="multipart/form-data" onsubmit="setGoalType()">
            <div class="input-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>

            <div class="input-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="mobile">Mobile:</label>
            <input type="tel" id="mobile" name="mobile" pattern="[6-9][0-9]{9}" required>

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required>

            <label for="weight">Weight (kg):</label>
            <input type="number" id="weight" name="weight" step="0.1" required>

            <label for="height">Height (cm):</label>
            <input type="number" id="height" name="height" step="0.1" required>

            <label for="fitness_goals">Fitness Goals:</label>
            <input type="text" id="fitness_goals" name="fitness_goals" required>

            <label for="goal_type">Goal Type:</label>
            <select id="goal_type" name="goal_type" required>
                <option value="weight_loss">Weight Loss</option>
                <option value="weight_gain">Weight Gain</option>
            </select>
            <input type="hidden" id="selected_goal" name="selected_goal">

            <label for="dietary_preferences">Dietary Preferences:</label>
            <select id="dietary_preferences" name="dietary_preferences" required>
                <option value="vegetarian">Vegetarian</option>
                <option value="non_vegetarian">Non-Vegetarian</option>
            </select>

            <label for="activity_level">Activity Level:</label>
            <select id="activity_level" name="activity_level" required>
                <option value="sedentary">Sedentary</option>
                <option value="moderate">Moderate</option>
                <option value="active">Active</option>
            </select>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Register</button>
        </form>
    </div>
    <script>
        function setGoalType() {
            const goalType = document.getElementById('goal_type').value;
            document.getElementById('selected_goal').value = goalType;
        }
    </script>
</body>
</html>
