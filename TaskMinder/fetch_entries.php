<?php
session_start();
$host = 'localhost:3308';
$username = 'root';
$password = '';
$database = 'task_manager';
$mysqli = new mysqli($host, $username, $password, $database);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}
$userID = $_SESSION['user_id'];
$query = "SELECT * FROM journal WHERE user_id = $userID ORDER BY entry_date DESC";
$result = $mysqli->query($query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $entryDate = $row['entry_date'];
        $entryText = $row['entry_text'];
        echo '<div class="journal-box">';
        echo "<strong>Date:</strong> $entryDate<br>";
        echo "<p>$entryText</p>";
        echo '</div>';
    }
} else {
    echo "No entries found.";
}
$mysqli->close();
?>