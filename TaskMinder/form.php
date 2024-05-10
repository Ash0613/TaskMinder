<?php

 session_start();
if (!isset($_SESSION['user_id'])) {
     header("Location: login.php");
    exit; 
}

 $servername = "localhost:3308";  
$username = "root";  
$password = "";  
$database = "task_manager";  

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

 function processTasks($conn) {
    
     date_default_timezone_set('Asia/Kolkata');  

    $today = date('Y-m-d');

     $currentTime = time();

     $tasksQuery = "SELECT task_name, priority, task_date, task_time, notes FROM tasks";
    $tasksResult = $conn->query($tasksQuery);
    if (!$tasksResult) {
        die("Error executing tasks query: " . $conn->error);
    }
    if ($tasksResult->num_rows > 0) {
         while ($task = $tasksResult->fetch_assoc()) {
            $taskDate = $task['task_date'];
            $taskTime = $task['task_time'];
            $notes = $task['notes'];

             if ($taskDate == $today) {
                $task_Time = strtotime($taskTime); 

                
                $fiveMinutesBeforeTask = $task_Time - (5 * 60); // 5 minutes in seconds

                 if ($currentTime >= $fiveMinutesBeforeTask && $currentTime <= $task_Time) { 
                     $taskName = $task['task_name'];
                    $priority = $task['priority'];

                    sendSMS("Task: $taskName \nPriority: $priority \nNotes: $notes \nAt: $taskTime \n" . $taskDate, $conn);
                }
            }
        }
    } else {
        echo "No tasks found.";
    }
}
function processGoals($conn) {
     $goalsQuery = "SELECT id, goal_name, due_date, notification_sent FROM goals";
    $goalsResult = $conn->query($goalsQuery);
    if (!$goalsResult) {
        die("Error executing goals query: " . $conn->error);
    }
    if ($goalsResult->num_rows > 0) {
         while ($goal = $goalsResult->fetch_assoc()) {
            $goalID = $goal['id'];
            $goalStartDate = $goal['due_date'];
            $goalName = $goal['goal_name'];
            $notificationSent = $goal['notification_sent'];
             if ($goalStartDate == date('Y-m-d') && !$notificationSent) {
                 sendSMS("Here's a reminder that your\n Goal: $goalName \n Due Date:\n" . $goalStartDate, $conn);
                 $updateQuery = "UPDATE goals SET notification_sent = 1 WHERE id = $goalID";
                $conn->query($updateQuery);
            }
        }
    } else {
        echo "No goals found.";
    }
}



use Twilio\Rest\Client;
 function sendSMS($message, $conn) {
    $twilioNumber = "Your twilio number";
    $accountSid = "Your twilio account sid";
    $authToken = "Your twilio auth token";
    
    require __DIR__ . "/vendor/autoload.php";

    $client = new Client($accountSid, $authToken);  

     $usersQuery = "SELECT phone FROM users";
    $result = $conn->query($usersQuery);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (!empty($row["phone"])) {
                // to Send SMS message
                $client->messages->create(
                    $row["phone"],
                    [
                        "from" => $twilioNumber,
                        "body" => $message
                    ]
                );
            }
        }
    }
}
