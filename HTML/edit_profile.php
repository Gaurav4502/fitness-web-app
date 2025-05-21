<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_page.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "fitness_app");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $weight = floatval($_POST['weight']);
    $fitness_goals = htmlspecialchars($_POST['fitness_goals']);

    if (!empty($_FILES["profile_pic"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ["jpg", "jpeg", "png"];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
                $profile_pic = basename($_FILES["profile_pic"]["name"]);
                $sql = "UPDATE users SET weight = ?, fitness_goals = ?, profile_pic = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("dssi", $weight, $fitness_goals, $profile_pic, $user_id);
            }
        }
    } else {
        $sql = "UPDATE users SET weight = ?, fitness_goals = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dsi", $weight, $fitness_goals, $user_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Profile updated!'); window.location.href='dashboard.php';</script>";
    }
    $stmt->close();
}
$conn->close();
?>

<form action="" method="POST" enctype="multipart/form-data">
    <label>New Weight:</label>
    <input type="number" name="weight" required>
    <label>New Fitness Goal:</label>
    <textarea name="fitness_goals" required></textarea>
    <label>Change Profile Picture:</label>
    <input type="file" name="profile_pic">
    <button type="submit">Save Changes</button>
</form>
