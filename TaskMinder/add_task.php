<?php
 session_start();

 if (!isset($_SESSION['user_id'])) {
     header("Location: login.php");  
    exit();  
}

// Database connection
$servername = "localhost:3308";  
$username = "root";  
$password = "";  
$database = "task_manager";  

 $conn = new mysqli($servername, $username, $password, $database);

 if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $taskName = $_POST['taskName'];
    $priority = $_POST['priority'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $notes = $_POST['notes'];

     $userId = $_SESSION["user_id"];

     $sql = "INSERT INTO tasks (user_id, task_name, priority, task_date, task_time, notes) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

     if (!$stmt) {
        die("Error in SQL query: " . $conn->error);
    }

    // Bind parameters 
    $stmt->bind_param("isssss", $userId, $taskName, $priority, $date, $time, $notes);
    $stmt->execute();
    $stmt->close();
}

// to check if AJAX request is sent for deleting tasks
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['taskCheckbox'])) {
     foreach ($_POST['taskCheckbox'] as $taskId) {
        // Delete the task from the database
        $sql = "DELETE FROM tasks WHERE task_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $taskId);
        $stmt->execute();
        $stmt->close();
    }
    exit();  
}

 $sql = "SELECT * FROM tasks WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

 $tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}
$stmt->close();

// Encode tasks into JSON format
$tasks_json = json_encode($tasks);

// to retrieve completed tasks from localStorage
$completedTasks = isset($_SESSION['completedTasks']) ? json_decode($_SESSION['completedTasks'], true) : [];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task - TASKMINDER</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
       .sidenav {
            display: flex;
            flex-direction: column;
             align-items: flex-start;
            height: 100%;
            width: 260px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: rgba(188, 133, 163, 0.8);
            border-right: 2px solid rgb(236, 219, 220);
            border-color: rgb(236, 219, 220);
            padding-top: 0px;
            opacity: 0.9;
             
        }

        .sidenav .horizontal {
            display: flex;
             align-items: center;
            margin-top: 0;

            height: 50px;
            width: 100%;
            margin-left: 0px;
            background-color: rgb(145, 92, 121);

        }

        .sidenav a {

            text-decoration: none;
            font-size: 18px;
            color: #ffffff;
            display: block;
            transition: 0.3s;
            margin-left: 20px;
        }

        .sidenav .atask {
            margin-left: 10px;
            margin-right: 60px;
        }

        .sidenav .a2 {
            padding: 10px 10px 10px 14px;
             margin: 10px 0;
            text-decoration: none;
            font-size: 22px;
            color: #ffffff;
            display: block;
            transition: 0.3s;
            margin-top: 35px;
            margin-left: 19px;

        }


        .sidenav a:hover {
            color: #131111;

        }


         /* Import Google font - Poppins */
         @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
}

body {
            display: flex;
            padding: 0 10px;
            min-height: 100vh;
            background-image: url('images/vecteezy_pink-pastel-color-abstract-background-design-and-soft-pastel_9279989.jpg');
            background-size: cover;
            background-position: center;
        }

.task-form-container {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 300px;
            background-color: #fff;
             border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
             padding: 20px;
            display: none;
             z-index: 1000;
            
         }

        .task-form-container.active {
            display: block;
         }

        .task-form-container h2 {
            color: #915c79;
             font-size: 24px;
            margin-top: 0;
        }

        .task-form-container .input-group {
            margin-bottom: 20px;
        }

        .task-form-container textarea {
            height: 100px;
             resize: none;
         }

        .task-form-container button[type="submit"] {
            background-color: #915c79;
             color: #fff;
             border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .task-form-container button[type="submit"]:hover {
            background-color: #724757;
         }

         .add-task-btn {
            position: fixed;
            bottom: 50px;
            right: 50px;
            width: 70px;
            height: 70px;
            background-color: #915c79;
             color: #fff;
             border-radius: 50%;
            font-size: 50px;
            text-align: center;
            line-height: 50px;
            cursor: pointer;
            outline: none;
            z-index: 1000;
         }

        .add-task-btn:hover {
            background-color: #724757;
             

        }

         .task-list {
            padding: 20px;
            margin-top: 70px;
            margin-left: 300px;
             width: 450px;
            background-color: #fff;
             border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
             z-index: 999;
             position: absolute;
             max-height: 600px;  
            overflow-y: auto;
        }

        .completed-tasks {
            padding: 20px;
            margin-top: 70px;
            margin-left: 800px;
             width: 450px;
            background-color: #fff;
             border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
             z-index: 999;
             position: absolute;
             max-height: 600px;  
            overflow-y: auto;
        }

        

       

        .task-group {
            padding: 15px;
        }

        .task-item {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
            
        }
        

        .task-item label {
            font-size: 18px;
            font-weight: bold;
        }

        .task-details {
    
    display: flex;  
    flex-direction: column; 
    
    justify-content: space-between;
    padding:15px;
}



.task-beta span {
    display: block;  
}
.task-beta {
    display: flex;  
    flex-wrap: wrap;  
}

.task-beta span {
    margin-right: 20px;  
    margin-top: 10px;;
}

 .task-list::-webkit-scrollbar {
    width: 5px;  
}

.task-list::-webkit-scrollbar-track {
    background-color: transparent;  
}

.task-list::-webkit-scrollbar-thumb {
    background-color: #888;  
    border-radius: 5px;  
}


 .completed-tasks::-webkit-scrollbar {
    width: 5px; 
}

.completed-tasks::-webkit-scrollbar-track {
    background-color: transparent;  
}

.completed-tasks::-webkit-scrollbar-thumb {
    background-color: #888;  
    border-radius: 5px;  
}




.completed-tasks h2 {
            color: #915c79;
            font-size: 30px;
            margin-top: 0;
            font-family: 'Futura', sans-serif;
            text-align: center;

        }

        .completed-tasks .task-group {
            margin-top: 20px;
        }

        .completed-tasks .task-item {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .task-list .task-group {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .completed-tasks .task-item label {
            font-size: 18px;
            font-weight: bold;
        }

        .completed-tasks .task-details {
            display: flex;
            padding:15px;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 17px;
            color: black;
        }

        .completed-tasks .task-details span {
            margin-right: 10px;
        }



        

        .task-list h2 {
            color: #915c79;
            font-size: 30px;
            margin-top: 0;
            margin-bottom: 10px;
            font-family: 'Futura', sans-serif;
            text-align: center;

        }

        .task-form-container {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 300px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: none;
            z-index: 1000;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
            background: transparent;
            border: none;
        }

        .close-btn:hover {
            color: red;
        }

        .cc{
            height:15px;
            width:15px;
            margin-top: 6px;
            margin-right: 25px;
        }

         




.task-detail .task-meta span {
    margin-top: 10px;
}
.task-items{
    display: flex;
    flex-direction: row;  
    border-bottom: 1px solid #ddd;
    padding-bottom: 20px;
    margin-bottom: 20px;
    
}
.task-alpha{
    padding-right:5px;
}



.task-detail span:nth-child(2),
.task-detail span:nth-child(3),
.task-detail span:nth-child(4),
.task-detail span:nth-child(5) {
    color:black;

}










        
        


    </style>
</head>

<body>
<div class="sidenav">
    <div class="horizontal">
      <a class="atask" href="getstarted.html">
        <i class="fas fa-tasks"></i>
        TaskMinder
      </a>
      <a class="userprof" href="userprof.php">
        <i class="fas fa-user profile-icon"></i> <!-- Profile Icon -->

      </a>
    </div>
    <a class="a2" href="home.php">
      <i class="fas fa-home"></i> <!-- Tasks Icon -->
      Home
    </a>
    <a class="a2" href="add_task.php">
      <i class="fas fa-tasks profile-icon"></i> <!-- Tasks Icon -->
      Add Task
    </a>
    <a class="a2" href="notes.html">
            <i class="fas fa-sticky-note profile-icon"></i> <!-- Goals Icon -->
            Notes
    </a>
    <a class="a2" href="journal.html">
      <i class="fas fa-book profile-icon"></i> <!-- Journal Icon -->
      Journal
    </a>
    <a class="a2" href="prevJournals.php">
      <i class="fas fa-history profile-icon"></i> <!-- History Icon -->
      Previous Journal's
    </a>
    <a class="a2" href="goal.php">
      <i class="fas fa-bullseye profile-icon"></i> <!-- Goals Icon -->
      Add Goals
    </a>
    <a class="a2" href="aboutus1.html" class="upcoming-tasks-icon">
      <i class="fas fa-users profile-icon"></i> <!-- Calendar Icon -->
      About Us
    </a>
  </div>
  <img src="images/taskimage.png" id="introImage" alt="Intro Image"
        style="position: absolute; top: 50%; left: 55%; transform: translate(-50%, -50%);height: 180px; width: 180px;">


    <div class="task-form-container" id="taskFormContainer">
        <h2>Add Task</h2>
        <button class="close-btn" id="closeTaskFormBtn">x</button>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="input-group">
                <label for="taskName">Task Name</label>
                <input type="text" id="taskName" name="taskName" required>
            </div>
            <div class="input-group">
                <label for="priority">Priority</label>
                <select id="priority" name="priority">
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
            <div class="input-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date">
            </div>
            <div class="input-group">
                <label for="time">Time</label>
                <input type="time" id="time" name="time">
            </div>
            <div class="input-group">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes"></textarea>
            </div>
            <button type="submit" id="addTaskBtn">Add Task</button>
        </form>
    </div>
    <button class="add-task-btn" id="showTaskFormBtn">+</button>

    <div class="task-list" id="taskList">
        <h2>Task's List</h2>
        <?php foreach ($tasks as $task): ?>
            <div class="task-items">
            
                <input class="cc" type="checkbox" name="taskCheckbox[]" value="<?php echo $task['task_id']; ?>" onclick="deleteTask(this)">
                
                <div class="task-detail">
                    <div><span style="font-weight: bold; color: black;"><?php echo $task['task_name']; ?></span></div>
                    <span class="task-alpha">Priority: <?php echo $task['priority']; ?></span>
                    <span class="task-alpha">Date: <?php echo $task['task_date']; ?></span>
                    <span class="task-alpha">Time: <?php echo $task['task_time']; ?></span>
                    <span>Notes: <?php echo $task['notes']; ?></span>
                </div>
        
            </div>
        <?php endforeach; ?>
    </div>


    <div class="completed-tasks" id="completedTasks">
    <h2>Completed Task List</h2>
    <div id="completedTaskItems">
    <?php
     $completedTasks = isset($_SESSION['completedTasks']) ? json_decode($_SESSION['completedTasks'], true) : [];

     foreach ($completedTasks as $task) {
        echo '<div class="task-item">';
        echo '<div class="task-details">';
        echo '<span>' . $task['taskName'] . '</span>';
        echo '<span>Priority: ' . $task['priority'] . '</span>';
        echo '<span>Date: ' . $task['taskDate'] . '</span>';
        echo '<span>Time: ' . $task['taskTime'] . '</span>';
        echo '<span>Notes: ' . $task['notes'] . '</span>';
        echo '</div>';
        echo '</div>';
    }
    ?>
    </div>
</div>


<script>

document.addEventListener('DOMContentLoaded', function () {
    // Retrieve completed tasks from localStorage
    var completedTasks = localStorage.getItem('completedTasks');

    // Check if there are completed tasks
    if (completedTasks) {
        // Parse completed tasks from JSON
        completedTasks = JSON.parse(completedTasks);
        
        // Loop through completed tasks and render them
        completedTasks.forEach(function(task) {
            var taskItem = document.createElement('div');
            taskItem.classList.add('task-item');
            
            var taskDetails = document.createElement('div');
            taskDetails.classList.add('task-details');
            
            // Add task details to task item
            taskDetails.innerHTML = `
            <div class="task-alpha">
               <span>${task.taskName} - <span style="color: grey;">${task.taskDate}</span></span>
            </div>
            <div class="task-beta">
                <span>Priority: ${task.priority}</span>
                <span>Time: ${task.taskTime}</span>
                <span>Notes: ${task.notes}</span>
            </div>
            `;

            
            taskItem.appendChild(taskDetails);
            document.getElementById('completedTaskItems').appendChild(taskItem);
        });
    } else {
        // If  no completed tasks
        var noTasksMessage = document.createElement('p');
        noTasksMessage.textContent = 'No completed tasks yet.';
        document.getElementById('completedTaskItems').appendChild(noTasksMessage);
    }
});

document.addEventListener('DOMContentLoaded', function () {
       var today = new Date().toISOString().split('T')[0];
      
       document.getElementById('date').setAttribute('min', today);
    });




        

function deleteTask(checkbox) {
     var taskItem = checkbox.closest(".task-items");

     var taskDetails = {
        taskName: taskItem.querySelector(".task-detail span:nth-child(1)").textContent,
        priority: taskItem.querySelector(".task-detail span:nth-child(2)").textContent.split(": ")[1],
        taskDate: taskItem.querySelector(".task-detail span:nth-child(3)").textContent.split(": ")[1],
        taskTime: taskItem.querySelector(".task-detail span:nth-child(4)").textContent.split(": ")[1],
        notes: taskItem.querySelector(".task-detail span:nth-child(5)").textContent.split(": ")[1]
    };

      var completedTasks = localStorage.getItem('completedTasks');
    if (completedTasks) {
         completedTasks = JSON.parse(completedTasks);
    } else {
         completedTasks = [];
    }

     completedTasks.push(taskDetails);

     localStorage.setItem('completedTasks', JSON.stringify(completedTasks));



     var taskId = checkbox.value;
    console.log("Deleting task with ID: " + taskId);  

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            console.log(xhr.responseText);  
            if (xhr.status == 200) {
                 
                taskItem.remove();
            } else {
                console.error("Error deleting task: " + xhr.statusText);
            }
        }
    };
    xhr.send("taskCheckbox[]=" + taskId);
}


        

        
    
     
    

   

    document.addEventListener("DOMContentLoaded", function () {
        const taskFormContainer = document.getElementById("taskFormContainer");
        const showTaskFormBtn = document.getElementById("showTaskFormBtn");
        const closeTaskFormBtn = document.getElementById("closeTaskFormBtn");
        const introImage = document.getElementById("introImage");

        
        

        closeTaskFormBtn.addEventListener("click", function () {
            taskFormContainer.style.display = "none";
        });

        showTaskFormBtn.addEventListener("click", function () {
            taskFormContainer.style.display = "block";
            introImage.style.display = "none";  
        });

        
         
        addTasksToPage(tasks);

         
        if (tasks.length === 0) {
            const mainHeading = document.createElement("h2");
            mainHeading.textContent = "Tasks";
            taskList.appendChild(mainHeading);
        }

        
    });
    

    

    
</script>

</body>

</html>
