<?php
session_start();

 if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");  
    exit();
}

 $servername = "localhost:3308";  
$username_db = "root"; 
$password_db = "";  
$dbname = "task_manager";  

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

 if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

 $sql = "SELECT id, username, email, phone FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
} else {
    echo "User not found!";
    exit();
}

 if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_username'])) {
    $new_username = $_POST['new_username'];

 
     $update_sql = "UPDATE users SET username = '$new_username' WHERE id = $user_id";

    if ($conn->query($update_sql) === TRUE) {
         $_SESSION['username'] = $new_username;
        $user_data['username'] = $new_username;
        echo '<p style="position: absolute;
        top: 10px; 
        left: 0;
        right: 0;
        margin: auto;
        width: fit-content;
        background-color: rgb(145, 92, 121); 
        color: white;
        padding: 10px;
        border-radius: 5px;
        text-align: center;">Username updated successfully</p>';

    } else {
        echo "Error updating username: " . $conn->error;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
     <!-- Google Fonts -->
     <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>User Profile</title>
    <style>
        body {
            display: flex;
            flex-direction: column; 
            align-items: center;
            padding: 0 10px;
            justify-content: center;
            min-height: 100vh;
            background-image: url('images/vecteezy_pink-pastel-color-abstract-background-design-and-soft-pastel_9279989.jpg');
            background-size: cover;
            background-position: center;

        
        }
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
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
        .sidenav .horizontal a {
            margin-bottom: 20px;
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



        .container {
            display: flex;
            align-items: center;
            height:80vh;
            width: 690px;
            margin-top: 1px;
            margin-left: 300px;
            margin-bottom: 100px;
             background-color: rgba(255, 255, 255, 0.8);
             padding: 40px;
            padding-bottom: 40px;
             border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(243, 243, 243, 0.1);
            position: relative;
             z-index: 1;
            border: 2px solid rgba(188, 133, 163, 0.8);
         }

        .image-container {
            flex: 1;
            padding-left: 5px;
            padding-right: 0px;
            padding-top: 50px;
            padding-bottom: 50px;
           
           
        }

        .image-container img {
            width:90%;
            border-radius: 10px;
            height: 75vh;
        }

        .form-container {
            flex: 1;
        }



        h1 {
            text-align: center;
            color: rgba(188, 133, 163);
            margin-bottom: 50px;
        }

        .p1 {
            width: calc(100% - 20px);
             padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            padding-left: 5px;
             background-color: rgba(254, 231, 241, 0.8);
            
        }

        form {
            margin-top: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: rgb(170, 111, 115);  
        }

        input[type="text"] {
            width: calc(100% - 45px);
           
             padding: 5px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            padding-left: 35px;
             background-color: rgba(254, 231, 241, 0.8);
         }

        button[type="submit"] {
            background-color: rgb(188, 133, 163);  
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: rgb(170, 111, 115);  
        }

        a {
            color: rgb(188, 133, 163); 
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
        }

        a:hover {
            color: rgb(170, 111, 115); 
        }

        footer {
    text-align: center;
    padding: 20px 0;
    background-color: #915c79;
    color: #ffffff;
    width: 100%;  
    position: fixed;  
    bottom: 0; 
    left: 0;  
    margin-left:260px;
    align-self: center;
    
}

footer p {
    margin: 0;
    font-size: 14px;
    text-align: center;
    margin-right: 200px;
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

    <div class="container">
    <div class="image-container">
            <img src="https://wallpapers.com/images/high/aesthetic-pink-anime-laptop-and-phone-yilrjyvgxqg8knd7.webp" alt="Task Manager Image">
        </div>
        <div class="form-container">
        <h1>User Profile</h1>
        <p class="p1"><strong>User ID:</strong> <?php echo $user_data['id']; ?></p>
        <p class="p1"><strong>Username:</strong> <?php echo $user_data['username']; ?></p>
        <p class="p1"><strong>Email:</strong> <?php echo $user_data['email']; ?></p>
        <p class="p1"><strong>Phone:</strong> <?php echo $user_data['phone']; ?></p>
          
        <form action="" method="POST">
            <label for="new_username">New Username:</label>
            <input type="text" id="new_username" name="new_username" required>
            <button type="submit" name="change_username">Change Username</button>
        </form>

        <a href="logout.php">Logout</a> 
    </div>
    </div>
    
</body>

</html>
