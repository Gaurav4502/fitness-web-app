<?php
session_start();
include 'db_connect_admin.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Fetch admin details
$sql = "SELECT username FROM admins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Validate and sanitize search input
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = trim($_GET['search']);
    $search_query = htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8'); // Prevent XSS
    $sql_users = "SELECT id, first_name, last_name, gender, email, mobile, dob, weight, height, fitness_goals, goal_type, profile_pic, created_at 
                  FROM users 
                  WHERE (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR mobile LIKE ?)
                  AND created_at <= NOW() -- Prevent future registration dates
                  ORDER BY id ASC";
    $stmt_search = $conn->prepare($sql_users);
    $search_param = "%" . $search_query . "%";
    $stmt_search->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
    $stmt_search->execute();
    $result_users = $stmt_search->get_result();
} else {
    $sql_users = "SELECT id, first_name, last_name, gender, email, mobile, dob, weight, height, fitness_goals, goal_type, created_at 
                  FROM users 
                  WHERE created_at <= NOW() -- Prevent future registration dates
                  ORDER BY id ASC";
    $result_users = $conn->query($sql_users);
}

// Total Users & Most Common Goal
$total_users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$top_goal = $conn->query("SELECT goal_type, COUNT(goal_type) as count FROM users GROUP BY goal_type ORDER BY count DESC LIMIT 1")->fetch_assoc()['goal_type'];

// Validate and sanitize message inputs
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $receiver_id = intval($_POST['receiver_id']); // Ensure receiver_id is an integer
    $message = trim($_POST['message']);
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); // Prevent XSS

    if (!empty($message)) {
        $sql_msg = "INSERT INTO messages (sender_id, receiver_id, message, sender_type) VALUES (?, ?, ?, 'admin')";
        $stmt_msg = $conn->prepare($sql_msg);
        $stmt_msg->bind_param("iis", $admin_id, $receiver_id, $message);
        $stmt_msg->execute();
    }
}

// Standardize names and validate email/mobile formats
function format_name($name) {
    return ucwords(strtolower(trim($name))); // Capitalize first letters
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL); // Validate email format
}

function validate_mobile($mobile) {
    return preg_match('/^\+?[0-9]{10,15}$/', $mobile); // Validate mobile format
}

// Fetch messages for each user (including user-to-admin messages)
$sql_messages = "SELECT messages.message, messages.timestamp, messages.sender_type, 
                        CASE 
                            WHEN messages.sender_type = 'user' THEN CONCAT(users.first_name, ' ', users.last_name)
                            ELSE 'Admin'
                        END AS sender_name
                 FROM messages
                 LEFT JOIN users ON messages.sender_id = users.id AND messages.sender_type = 'user'
                 WHERE messages.receiver_id = ? OR (messages.sender_id = ? AND messages.sender_type = 'user')
                 ORDER BY messages.timestamp DESC";
$stmt_messages = $conn->prepare($sql_messages);

// Check if statement is prepared correctly
if (!$stmt_messages) {
    die("SQL Error: " . $conn->error);
}
$stmt_messages->bind_param("ii", $admin_id, $admin_id);
$stmt_messages->execute();
$result_messages = $stmt_messages->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../CSS/admin_dashboard.css">
    <script>
        function toggleMessages() {
            const messagesContainer = document.getElementById('messagesContainer');
            messagesContainer.classList.toggle('active');
        }

        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('active');
        }

        // Sort table columns
        function sortTable(columnIndex) {
            const table = document.getElementById("userTable");
            const rows = Array.from(table.rows).slice(1);
            const isAscending = table.getAttribute("data-sort-order") === "asc";
            rows.sort((rowA, rowB) => {
                const cellA = rowA.cells[columnIndex].innerText.toLowerCase();
                const cellB = rowB.cells[columnIndex].innerText.toLowerCase();
                return isAscending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
            });
            rows.forEach(row => table.tBodies[0].appendChild(row));
            table.setAttribute("data-sort-order", isAscending ? "desc" : "asc");
        }

        // Filter table rows
        function filterTable() {
            const input = document.getElementById("searchInput").value.toLowerCase();
            const rows = document.querySelectorAll("#userTable tbody tr");
            rows.forEach(row => {
                const cells = Array.from(row.cells).slice(1, 4); // Search in Name, Email, Mobile
                const matches = cells.some(cell => cell.innerText.toLowerCase().includes(input));
                row.style.display = matches ? "" : "none";
            });
        }
    </script>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($admin['username']); ?></h1>
    
    <h3>Total Users: <?php echo $total_users; ?></h3>
    
    <!-- Logout Button -->
    <a href="logout_admin.php" class="logout">Logout</a>
    
    <!-- Search Form -->
    <form method="GET" class="search-form">
        <input type="text" id="searchInput" placeholder="Search by name, email, or mobile" class="search-input" onkeyup="filterTable()">
    </form>
    
    <h2>User Details</h2>
    <table class="user-table" id="userTable">
        <thead>
            <tr>
                <th onclick="sortTable(0)">ID</th>
                <th onclick="sortTable(1)">First Name</th>
                <th onclick="sortTable(2)">Last Name</th>
                <th onclick="sortTable(3)">Email</th>
                <th>Mobile</th>
                <th onclick="sortTable(5)">Registered On</th>
                <th>Send Message</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($user = $result_users->fetch_assoc()) : 
            $user['first_name'] = format_name($user['first_name']);
            $user['last_name'] = format_name($user['last_name']);
            $user['email'] = validate_email($user['email']) ? $user['email'] : 'Invalid Email';
            $user['mobile'] = validate_mobile($user['mobile']) ? $user['mobile'] : 'Invalid Mobile';
        ?>
        <tr>
            <td><?php echo htmlspecialchars($user['id']); ?></td>
            <td><?php echo htmlspecialchars($user['first_name']); ?></td>
            <td><?php echo htmlspecialchars($user['last_name']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars($user['mobile']); ?></td>
            <td><?php echo htmlspecialchars(date("d-m-Y", strtotime($user['created_at']))); ?></td>
            <td>
                <form method="POST" class="message-form">
                    <input type="hidden" name="receiver_id" value="<?php echo $user['id']; ?>">
                    <input type="text" name="message" placeholder="Enter message..." class="message-input" required>
                    <button type="submit" name="send_message" class="message-button">Send</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
