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

 if(isset($_GET['entry_id'])) {
    $entryID = $_GET['entry_id'];
    $query = "SELECT * FROM journal WHERE entry_id = $entryID";
    $result = $mysqli->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal Entry Details</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        body {
            background-image: url('images/vecteezy_pink-pastel-color-abstract-background-design-and-soft-pastel_9279989.jpg');
            background-size: cover;
            background-position: center;
            color: rgb(145, 92, 121);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            margin-top: 60px;
            margin-left: 300px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            width: 50%;
            overflow-y: auto;
            position: relative;
            min-height: 600px;
            
        }
        h1 {
            color: rgb(145, 92, 121);
            text-align: center;
            margin: 0;
            padding: 20px 0;
            font-size: 40px;
        }
        .entry-info {
            margin-top: 20px;
        }
        .entry-info p {
            margin-bottom: 10px;
            font-size: 20px;
            line-height: 1.6;
            position: relative;
        }
        .entry-info p.date,
        .entry-info p.entry {
             position: relative;
        }
        .entry-info p.date::before,
        .entry-info p.entry::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 1px;
            background-color: rgba(145, 92, 121, 0.5);
        }
        .back-btn {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 70%;
            text-align: center;
            text-decoration: none;
            background-color: rgb(145, 92, 121);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .back-btn:hover {
            background-color: #7a4a6f;
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
        <a class="a2" href="add_task.html">
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
        <a class="a2" href="aboutus.html" class="upcoming-tasks-icon">
         <i class="fas fa-users profile-icon"></i> <!-- Calendar Icon -->
         About Us
        </a>
    </div>
    <div class="container">
        <h1>Journal Entry Details</h1>
        <?php
        
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $entryDate = $row['entry_date'];
                $entryText = $row['entry_text'];
                 echo "<div class='entry-info'>";
                echo "<p class='date'><strong>Date:</strong> $entryDate</p>";
                echo "<p class='entry'><strong>Entry:</strong></p>";
                echo "<p>$entryText</p>";
                echo "</div>";
                echo "<a href='prevJournals.php' class='back-btn'>Back to Previous Journals</a>";
            } else {
                echo "<p>Journal entry not found.</p>";
            }
        } else {
            echo "<p>Entry ID not provided.</p>";
        }
        ?>
    </div>
</body>
</html>
