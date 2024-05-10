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

 if (isset($_POST['task_id'])) {
     $taskId = $_POST['task_id'];
     $sqlDelete = "DELETE FROM tasks WHERE id = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    if (!$stmtDelete) {
        echo "Error preparing statement: " . $conn->error;
    } else {
        $stmtDelete->bind_param("i", $taskId);
        $stmtDelete->execute();
        if ($stmtDelete->affected_rows > 0) {
            echo "Task deleted successfully";
        } else {
            echo "Error deleting task: " . $stmtDelete->error;
        }
         $stmtDelete->close();
    }
     $conn->close();
} else {
     echo 'Task ID not provided.';
}

?>
