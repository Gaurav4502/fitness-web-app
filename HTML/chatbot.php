<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "fitness_app";

// Database connection
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userMessage = trim($_POST["message"]);
    
    // Prepare and execute query
    $sql = "SELECT answer FROM chatbot WHERE question LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $userMessage . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $stmt->bind_result($answer);

    if ($stmt->fetch()) {
        echo $answer;
    } else {
        echo "Sorry, I don't have an answer for that.";
    }

    $stmt->close();
    $conn->close();
    exit; // Ensure no additional HTML is sent
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Chatbot</title>
    <link rel="stylesheet" href="../CSS/chatbot.css">  <!-- External CSS -->
</head>
<body>

<!-- Chatbot UI -->
<div id="chatbot">
    <div id="chatbox">
        <p class="botText"><span>Hi! Ask me anything about fitness.</span></p>
    </div>
    <div class="input-container">
        <input id="userInput" type="text" placeholder="Type your question..." onkeypress="handleKeyPress(event)">
        <button id="sendButton">Send</button>
    </div>
    <!-- Removed "Back to Dashboard" link -->
</div>

<script src="../JS/chatbot.js"></script> <!-- External JavaScript -->
</body>
</html>
