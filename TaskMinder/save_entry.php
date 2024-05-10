<?php
session_start();  

 if (isset($_SESSION['user_id'])) {
     $host = "localhost:3308";  
    $username = "root";  
    $password = "";  
    $database = "task_manager"; 

     $conn = new mysqli($host, $username, $password, $database);

     if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

     $sql = "INSERT INTO journal (user_id, entry_date, entry_text) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

     $user_id = $_SESSION['user_id'];  
    $entry_date = date("Y-m-d");  
    $entry_text = $_POST["journalEntry"];  

    $stmt->bind_param("iss", $user_id, $entry_date, $entry_text);
    $stmt->execute();

     $stmt->close();
    $conn->close();

     header("Location: journal.html");
    exit();
} else {
     header("Location: login.php"); 
    exit();
}
?>
