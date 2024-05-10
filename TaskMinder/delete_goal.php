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
 if (isset($_POST['goalId'])) {
     $goalId = $_POST['goalId'];

 
     $sqlDelete = "DELETE FROM goals WHERE id = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $goalId);
    $stmtDelete->execute();

     if ($stmtDelete->affected_rows > 0) {
        echo 'Goal deleted successfully.';
    } else {
        echo 'Error deleting goal.';
    }

     $stmtDelete->close();
    $conn->close();
} else {
     echo 'Goal ID not provided.';
}
?>
