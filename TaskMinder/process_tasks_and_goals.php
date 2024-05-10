<?php
 require_once "form.php";  

 $servername = "localhost:3308";  
$username = "root";  
$password = "";  
$database = "task_manager";  

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

processTasks($conn);
processGoals($conn);
?>
