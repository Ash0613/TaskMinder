<?php
 session_start();

 if (!isset($_SESSION['user_id'])) {
     header("Location: login.php");  
    exit();  
}

 $servername = "localhost:3308";  
$username = "root";  
$password = "";  
$database = "task_manager";  

 $conn = new mysqli($servername, $username, $password, $database);

 if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

 if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $goalName = $_POST["goalName"];
    $goalColor = $_POST["color"];
    $dueDate = $_POST["dueDate"];  
    $userId = $_SESSION["user_id"]; 

 $sql = "INSERT INTO goals (user_id, goal_name, due_date, color) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

 $stmt->bind_param("isss", $userId, $goalName, $dueDate, $goalColor);

 if ($stmt->execute()) {
    header("Location: goal.php");
    exit(); 
} else {
    echo "Error executing statement: " . $stmt->error;
}

 $stmt->close();

}

 $conn->close();
?>
