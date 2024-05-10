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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">


  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <title>My Journal Entries</title>
    <style>
       body {
            font-family: Arial, sans-serif;
            background-image: url('images/vecteezy_pink-pastel-color-abstract-background-design-and-soft-pastel_9279989.jpg');
            background-size: cover;
            background-position: center;
            color: #ffff;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            margin-left: 200px;
        }

        .prevjournal {
            margin-left: 500px;
            margin-top: 35px;
            color: rgb(145, 92, 121);
            font-size: 50px;
        }

        .swiper-container {
            display: flex;
            align-items: center;
            max-width: 80%;
            overflow-x: hidden;
            white-space: nowrap;
            margin-left: 290px;
            margin-right: 290px;
            margin-top: 200px;
            display: flex;
            position: relative;
            overflow: hidden;
            
        }

        .swiper-wrapper {
            display: flex;
            font-size: 25px;
            
        }

        .swiper-slide {
            flex: 0 0 auto;
            margin-left: 40px;
            margin-right: 40px;
            
        }
        

        .swiper-button-next,
        .swiper-button-prev {
            position: absolute;
            top: 50%;
            transform: translateY(-30%);
            width: 30px;
            height: 50px;
            color: rgb(145, 92, 121);
            cursor: pointer;
            z-index: 9;
            display: flex;
            justify-content: center;
            align-items: center;
            
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            color: grey;
        }

        .swiper-button-next {
            right: 0px;
        }

        .swiper-button-prev {
            left: 0px;
        }

        .journal-box {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            background-color: #ffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            width: 200px;
            height: 200px;
            overflow: hidden;
            position: relative;
            margin-bottom: 20px;
            
            
        }

        .journal-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .journal-details {
    padding: 40px;
    margin-top: 35px;
    padding-bottom: 0px;
    justify-content: center;
    display: flex;
    align-items: center;
    flex-direction: column;
}

        .journal-details a {
            color: rgb(145, 92, 121);
            text-decoration: none;
        }

        .journal-details a:hover {
            color: #7a4a6f;
        }

        .swiper-slide:last-child {
            margin-right: 0;
        }

        ::-webkit-scrollbar {
            display: none;
        }
        

        

        .sidenav {
            display: flex;
            flex-direction: column;
            /* Stack items vertically */
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
            opacity: 1;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
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

        a {

text-decoration: none;
font-size: 27px;
color: #ffffff;
display: block;
transition: 0.3s;
}

        .userprof {
            margin-left: 24px;
        }
        ::-webkit-scrollbar {
            display: none;
        }

        
       


        footer {
            background-color: #915c79;
            color: #ffffff;
            opacity: 0.6;

            text-align: center;
            padding: 7px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            margin-left: 60px;
            

        }

        footer p {

            text-align: center;
            margin-right: 260px;
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


    <h2 class="prevjournal">Previous Journal</h2>
    <div class="swiper-container">
        <div class="swiper-wrapper">
        <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $entryDate = $row['entry_date'];
                    $entryText = $row['entry_text'];
                    $entryID = $row['entry_id'];
                    echo '<div class="swiper-slide">';
                    echo '<div class="journal-box">';
                    echo '<div class="journal-details">';
                    echo "<a href='journal_details.php?entry_id=$entryID'><strong>Date:<br></strong> $entryDate</a><br>";
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "No entries found.";
            }
            ?>
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>  
    </div>
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var swiper = new Swiper('.swiper-container', {
        slidesPerView: '3',
        spaceBetween: 10,  
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
      });
    });
  </script>
  

    
</body>

</html>