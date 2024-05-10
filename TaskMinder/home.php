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

 $userId = $_SESSION["user_id"];

 $today = date("Y-m-d");
$weekStartDate = date("Y-m-d", strtotime('monday this week', strtotime($today)));
$weekEndDate = date("Y-m-d", strtotime('sunday this week', strtotime($today)));

 $sqlThisWeek = "SELECT id, goal_name, color, due_date FROM goals WHERE user_id = ? AND due_date BETWEEN ? AND ?";
$stmtThisWeek = $conn->prepare($sqlThisWeek);
$stmtThisWeek->bind_param("iss", $userId, $weekStartDate, $weekEndDate);
$stmtThisWeek->execute();
$resultThisWeek = $stmtThisWeek->get_result();

 $todaysGoals = [];
 $goalsDueDatesThisWeek = [];

 while ($row = $resultThisWeek->fetch_assoc()) {
    $todaysGoals[] = $row;
    $goalsDueDatesThisWeek[] = [
        'due_date' => $row['due_date'],
        'color' => $row['color']
    ];
}

 $stmtThisWeek->close();


 $sqlAllTasks = "SELECT task_date FROM tasks WHERE user_id = ?";
$stmtAllTasks = $conn->prepare($sqlAllTasks);
$stmtAllTasks->bind_param("i", $userId);
$stmtAllTasks->execute();
$resultAllTasks = $stmtAllTasks->get_result();

 $allTasks = [];

 while ($row = $resultAllTasks->fetch_assoc()) {
    $allTasks[] = $row['task_date'];
}

 $stmtAllTasks->close();




// Fetch all existing due dates for the user's goals from the database
$sqlAllDueDates = "SELECT DISTINCT due_date, color FROM goals WHERE user_id = ?";
$stmtAllDueDates = $conn->prepare($sqlAllDueDates);
$stmtAllDueDates->bind_param("i", $userId);
$stmtAllDueDates->execute();
$resultAllDueDates = $stmtAllDueDates->get_result();

// Array to store all existing due dates with colors
$allDueDatesWithColor = [];

// Process the fetched due dates and store them in an array with colors
while ($row = $resultAllDueDates->fetch_assoc()) {
    $allDueDatesWithColor[] = [
        'due_date' => $row['due_date'],
        'color' => $row['color']
    ];
}

 $stmtAllDueDates->close();

// Fetch tasks  
$sqlTasksThisWeek = "SELECT task_name, task_date, task_time FROM tasks WHERE user_id = ? AND task_date BETWEEN ? AND ?";
$stmtTasksThisWeek = $conn->prepare($sqlTasksThisWeek);
$stmtTasksThisWeek->bind_param("iss", $userId, $weekStartDate, $weekEndDate);
$stmtTasksThisWeek->execute();
$resultTasksThisWeek = $stmtTasksThisWeek->get_result();

// Array to store user's tasks due this week
$todaysTasks = [];

// Process the fetched tasks for this week and store them in an array
while ($row = $resultTasksThisWeek->fetch_assoc()) {
    $todaysTasks[] = $row;
}


// Close statement for tasks this week
$stmtTasksThisWeek->close();

// Encode   arrays as JSON
$goalsDueDatesThisWeekJson = json_encode($goalsDueDatesThisWeek);
$allDueDatesWithColorJson = json_encode($allDueDatesWithColor);
$todaysTasksJson = json_encode($todaysTasks);
$allTasksJson = json_encode($allTasks);


// Send the JSON data to JavaScript  
echo '<script>';
echo 'const goalsDueDatesThisWeek = ' . $goalsDueDatesThisWeekJson . ';';
echo 'const allDueDatesWithColor = ' . $allDueDatesWithColorJson . ';';
echo 'const todaysTasks = ' . $todaysTasksJson . ';';
echo 'const allTasks = ' . $allTasksJson . ';';
echo '</script>';

 $conn->close();
?>









<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="script.js" defer></script>

     <style>

        

.active li::before {
            position: absolute;
            content: "";
            left: 50%;
            top: 50%;
            height: 40px;
            width: 40px;
            z-index: -1;
            border-radius: 50%;
            transform: translate(-50%, -50%);
        } 

.li.goal-date {
    background: black;
    cursor: none;
}


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


        .goals-cont{
            margin-top: 40px;
        }

        .goals {
           margin-left: 300px;  
           margin-top: 60px;  
           max-height: 200px;  
           overflow-y: auto;  
           width:450px;
        }


.goals-content {
    padding-right: 15px;
}

.goals ul {
    list-style-type: none;
    padding: 0;
    margin: 20px 0;  
}

.goals li {
    
    
    margin-bottom: 10px; 
    padding: 10px;
    font-size: 18px;  
    font-family: 'Poppins', sans-serif; 
    
}
.goals-head{
    position: fixed;
    padding: 10px;
    margin-left: 270px;  
    margin-top: 45px;

}



.tasks {
    font-size: 20px;
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
            align-items: center;
            padding: 0 10px;
            justify-content: center;
            min-height: 100vh;
            background-image: url('images/vecteezy_pink-pastel-color-abstract-background-design-and-soft-pastel_9279989.jpg');
            background-size: cover;
            background-position: center;
        }

        .wrapper {
            margin-left: 250px;
            width: 450px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .wrapper header {
            display: flex;
            align-items: top-right;
            padding: 25px 30px 10px;
            justify-content: space-between;
        }

        header .icons {
            display: flex;
        }

        header .icons span {
            height: 38px;
            width: 38px;
            margin: 0 1px;
            cursor: pointer;
            color: #878787;
            text-align: center;
            line-height: 38px;
            font-size: 1.9rem;
            user-select: none;
            border-radius: 50%;
        }

        .icons span:last-child {
            margin-right: -10px;
        }

        header .icons span:hover {
            background: #f2f2f2;
        }

        header .current-date {
            font-size: 1.45rem;
            font-weight: 500;
        }

        .calendar {
            padding: 20px;

        }

        .calendar ul {
            display: flex;
            flex-wrap: wrap;
            list-style: none;
            text-align: center;
        }

        .calendar .days {
            margin-bottom: 20px;
        }

        .calendar  li {
            color: #333;
            width: calc(100% / 7);
            font-size: 1.07rem;
        }

        .calendar .days li {
            color: #333;
            width: calc(100% / 7);
            font-size: 1.07rem;
        }

        .calendar .weeks li {
            font-weight: 500;
            cursor: default;
        }

        .calendar .days li {
            z-index: 1;
            cursor: pointer;
            position: relative;
            margin-top: 20px;
            margin-bottom: 20px;
            
        }

        .days li.inactive {
            color: #aaa;
        }

        .days li.active {
            color: #fff;
            
            
        }

        .days li::before {
            position: absolute;
            content: "";
            left: 50%;
            top: 50%;
            height: 40px;
            width: 40px;
            z-index: -1;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            
        }

        .days li.active::before {
            background: rgba(188, 133, 163, 0.8);
            
        }

        .days li:not(.active):hover::before {
            background: #f2f2f2;
        }



        
        





        .goals{
            margin-left: 260px;
            margin-top: 100px;
        }


        .color-box {
    width: 15px;  
    height: 15px;  
    margin-right: 5px;  
    display: inline-block;
    vertical-align: middle;  
    border-radius: 10%; 
}
.gt {
    display: flex; 
    flex-direction: column;  
    align-items: center;  
}

.tasks-cont,
.goals-cont {
    width: 100%;  
    max-width: 600px; 
    margin-bottom: 20px; 
}

.tasks-head,
.goals-head {
    text-align: center;  
}

.tasks {
   
    border-radius: 10px;  
    margin-left: 150px;
    padding: 20px;  
}
.tasks-cont{
    margin-left: 250px;;
}

.tasks-head,
.goals-head {
    padding: 10px 0;  
}



.tasks-cont {
    width: 100%;  
    max-width: 600px;  
    margin-bottom: 20px;  
}

.tasks-head {
    text-align: center;  
}

.tasks {
    border-radius: 10px; 
    padding: 20px;  
}




.black-dot {
    position: absolute;
    top: -3px;  
    left: 40%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 5px solid transparent;  
    border-right: 5px solid transparent;  
    border-top: 8px solid black;  
    transform: rotate(360deg);
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
            <a href="userprof.php">
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
    <div class="gt">
    <div class="tasks-cont">
    <h1 class="tasks-head">Tasks for this week:</h1>
    <div class="tasks" id="tasksContainer">
        <!-- Display tasks for this week -->
    <?php if (!empty($todaysTasks)) : ?>
        <ul>
            <?php foreach ($todaysTasks as $task) : ?>
                <li>
                    <div class="task-details">
                        <span><?php echo htmlspecialchars($task['task_name']); ?></span>
                        <span style="color: #777;">- Date: <?php echo htmlspecialchars($task['task_date']); ?></span>
                        <span style="color: #777;">  Time: <?php echo htmlspecialchars($task['task_time']); ?></span>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>No tasks due this week.</p>
    <?php endif; ?>
</div>
    </div>

    <div class="goals-cont">
    <h1 class="goals-head">Goals due this week:</h1>
    <div class="goals" id="goalsContainer">
        <!-- Display goals due this week -->
        
        <?php if (!empty($todaysGoals)) : ?>
            <ul>
                <?php foreach ($todaysGoals as $goal) : ?>
                    <li>
                      <div class="color-box" style="background-color: <?php echo htmlspecialchars($goal['color']); ?>"></div>
                      <span style="font-weight: bold;"><?php echo htmlspecialchars($goal['goal_name']); ?></span>
                      <span style="color: #777;">- Due Date: <?php echo htmlspecialchars($goal['due_date']); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>No goals due this week.</p>
        <?php endif; ?>
    </div>
    </div>
    </div>

    


    <div class="wrapper">
        <header>
            <p class="current-date">March 2024</p>
            <div class="icons">
                <span id="prev" class="material-icons">chevron_left</span>
                <span id="next" class="material-icons">chevron_right</span>
            </div>
        </header>
        <div class="calendar">
            <ul class="weeks">
                <li>Sun</li>
                <li>Mon</li>
                <li>Tue</li>
                <li>Wed</li>
                <li>Thu</li>
                <li>Fri</li>
                <li>Sat</li>
            </ul>
            <ul class="days">
                <li class="inactive">25</li>
                <li class="inactive">26</li>
                <li class="inactive">27</li>
                <li class="inactive">28</li>
                <li class="inactive">29</li>
                <li>1</li>
                <li>2</li>
                <li>3</li>
                <li>4</li>
                <li>5</li>
                <li>6</li>
                <li>7</li>
                <li>8</li>
                <li>9</li>
                <li>10</li>
                <li>11</li>
                <li>12</li>
                <li>13</li>
                <li>14</li>
                <li>15</li>
                <li>16</li>
                <li class="active">17</li>
                <li>18</li>
                <li>19</li>
                <li>20</li>
                <li>21</li>
                <li>22</li>
                <li>23</li>
                <li>24</li>
                <li>25</li>
                <li>26</li>
                <li>27</li>
                <li>28</li>
                <li>29</li>
                <li>30</li>
                <li>31</li>
                <li class="inactive">1</li>
                <li class="inactive">2</li>
                <li class="inactive">3</li>
                <li class="inactive">4</li>
                <li class="inactive">5</li>
                <li class="inactive">6</li>


            </ul>
        </div>
    </div>

    <script>

        


    const daysTag = document.querySelector(".days"),
        currentDate = document.querySelector(".current-date"),
        prevNextIcon = document.querySelectorAll(".icons span");

     console.log(allDueDatesWithColor);

    let date = new Date(),
        currYear = date.getFullYear(),
        currMonth = date.getMonth();

    const months = ["January", "February", "March", "April", "May", "June", "July",
        "August", "September", "October", "November", "December"];

        const renderCalendar = () => {
    let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(),
        lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(),
        lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(),
        lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate();
    let liTag = "";

    for (let i = firstDayofMonth; i > 0; i--) {
        liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
    }

    for (let i = 1; i <= lastDateofMonth; i++) {
        let isToday =
            i === date.getDate() &&
            currMonth === new Date().getMonth() &&
            currYear === new Date().getFullYear()
                ? "active"
                : "";

        let dueDate = `${currYear}-${(currMonth + 1).toString().padStart(2, "0")}-${i
            .toString()
            .padStart(2, "0")}`;

        // Check if the dueDate is in todaysTasks and add a dot if it is
        let hasTask = allTasks.includes(dueDate);
        let dot = hasTask ? '<div class="black-dot"></div>' : '';

        // Check if the dueDate is in goalsDueDatesThisWeek and get its color
        let goalDateObj = goalsDueDatesThisWeek.find((item) => item.due_date === dueDate);
        let bgColor = goalDateObj ? goalDateObj.color : "";

        // If there is a task or a goal, use background color and add dot
        if (hasTask || goalDateObj) {
            liTag += `<li class="${isToday}" style="background-color: ${bgColor};border-radius: 100%;
        height: 40px;
        margin-top: 10px;
        margin-bottom: 10px;
        width: 43px;
        margin-left: 10px;
        margin-right: 5px;
        display: flex;
        align-items: center;
        justify-content: center;">${i}${dot}</li>`;
        } else {
            liTag += `<li class="${isToday}" style="background-color: ${bgColor};
border-radius: 100%;
height: 40px;
margin-top: 10px;
margin-bottom: 10px;
width: 43px;
margin-left: 10px;
margin-right: 5px;
display: flex;
align-items: center;
justify-content: center;">${i}</li>`;
        }
    }

    for (let i = lastDayofMonth; i < 6; i++) {
        liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
    }
    currentDate.innerText = `${months[currMonth]} ${currYear}`;
    daysTag.innerHTML = liTag;
};

     renderCalendar();

    // Event listeners for prev and next icons
    prevNextIcon.forEach(icon => {
        icon.addEventListener("click", () => {
            if (icon.id === "prev") {
                currMonth--;
                if (currMonth < 0) {
                    currYear--;
                    currMonth = 11;
                }
            } else {
                currMonth++;
                if (currMonth > 11) {
                    currYear++;
                    currMonth = 0;
                }
            }
            renderCalendar();
        });
    });





        document.addEventListener('DOMContentLoaded', function () {
            const goalsContainer = document.getElementById('goalsContainer');

            // Add event listeners for mouse enter and leave
            goalsContainer.addEventListener('mouseenter', function () {
                // Show scrollbar when mouse enters the container
                goalsContainer.style.overflowY = 'auto';
            });

            goalsContainer.addEventListener('mouseleave', function () {
                // Hide scrollbar when mouse leaves the container
                goalsContainer.style.overflowY = 'hidden';
            });

            
        });




</script>



</body>

</html>