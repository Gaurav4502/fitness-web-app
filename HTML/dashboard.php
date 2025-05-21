<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_page.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "fitness_app");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details
$user_id = $_SESSION['user_id']; 
$sql = "SELECT first_name, last_name, weight, goal_type FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($first_name, $last_name, $weight, $goal_type);
$stmt->fetch();
$stmt->close();

// Set default profile picture if none is uploaded

// Fetch user messages with read status
$sql_messages = "SELECT id, message, created_at, status FROM messages WHERE receiver_id = ? ORDER BY id DESC"; 
$stmt_messages = $conn->prepare($sql_messages);
$stmt_messages->bind_param("i", $user_id);
$stmt_messages->execute();
$result_messages = $stmt_messages->get_result();
$stmt_messages->close();

// Mark messages as read
$update_status_sql = "UPDATE messages SET status = 'read' WHERE receiver_id = ? AND status = 'unread'";
$stmt_update_status = $conn->prepare($update_status_sql);
$stmt_update_status->bind_param("i", $user_id);
$stmt_update_status->execute();
$stmt_update_status->close();

// Handle message deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message_id'])) {
    $delete_message_id = intval($_POST['delete_message_id']);
    $delete_sql = "DELETE FROM messages WHERE id = ? AND receiver_id = ?";
    $stmt_delete = $conn->prepare($delete_sql);
    $stmt_delete->bind_param("ii", $delete_message_id, $user_id);
    $stmt_delete->execute();
    $stmt_delete->close();
    header("Location: dashboard.php");
    exit();
}

// Handle message sending (user to admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message_to_admin'])) {
    $message = trim($_POST['message_to_admin']);
    
    if (!empty($message)) {
        $sql_msg = "INSERT INTO messages (sender_id, receiver_id, message, sender_type) VALUES (?, ?, ?, 'user')";
        $stmt_msg = $conn->prepare($sql_msg);
        $admin_id = 1; // Assuming admin ID is 1
        $stmt_msg->bind_param("iis", $user_id, $admin_id, $message);
        $stmt_msg->execute();
        $stmt_msg->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../CSS/dashboard_style.css">
</head>
<body>

<header>
    <h1>Welcome, <?php echo htmlspecialchars($first_name); ?>!</h1>
    <nav>
        <ul id="nav-menu">
            <li><a href="../HTML/about.php">About</a></li>
            <li><a href="../HTML/logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<section class="how-it-works">
    <h2>How It Works</h2>
    <ul>
        <li>💪 Track your workouts effectively.</li>
        <li>🥗 Monitor your diet and nutrition.</li>
        <li>📊 Analyze your progress over time.</li>
        <li>🎯 Achieve your fitness goals with ease.</li>
    </ul>
</section>

<section class="quick-links">
    <h2>Quick Links</h2>
    <ul>
        <li><a href="../HTML/Workout_section.php">Workout Section</a></li>
        <li><a href="../HTML/Diet_page.php">Diet Page</a></li>
        <li><a href="../HTML/Progress.php">Progress Tracking</a></li>
        <!-- Removed Chatbot link -->
    </ul>
</section>

<section class="messages-section">
    <h2>Your Messages</h2>
    <ul class="messages-list">
        <?php if ($result_messages->num_rows > 0) : ?>
            <?php while ($message = $result_messages->fetch_assoc()) : ?>
                <li class="message-item <?php echo ($message['sender_type'] === 'user') ? 'sent' : 'received'; ?>">
                    <p><?php echo htmlspecialchars($message['message']); ?></p>
                    <small>
                        <?php echo date('F j, Y \a\t g:i A', strtotime($message['created_at'])); ?>
                    </small>
                </li>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="messages-placeholder">
                <i class="fas fa-envelope-open-text"></i>
                <p>No messages yet. Start a conversation!</p>
            </div>
        <?php endif; ?>
    </ul>
</section>

<section class="send-message-to-admin">
    <h2>Send Message to Admin</h2>
    <form method="POST">
        <textarea name="message_to_admin" placeholder="Enter your message..." required style="width: 100%; height: 100px; padding: 10px; margin-bottom: 10px;"></textarea>
        <button type="submit" name="send_message_to_admin" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">Send</button>
    </form>
</section>

<footer style="margin-top: 40px; padding: 20px; background: #343a40; color: white; text-align: center;">
    <p>&copy; 2025 Fitness App. All rights reserved.</p>
    <nav>
        <a href="../HTML/privacy_policy.php" style="color: #ffd700; text-decoration: none; margin: 0 10px;">Privacy Policy</a>
        <a href="../HTML/terms_of_service.php" style="color: #ffd700; text-decoration: none; margin: 0 10px;">Terms of Service</a>
    </nav>
</footer>

<script src="http://localhost/Fitness_web_app/JS/dashboard_script.js"></script>
</body>
</html>