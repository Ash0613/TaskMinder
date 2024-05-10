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

 $sql = "SELECT * FROM goals WHERE user_id = ? ORDER BY due_date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

 $userGoals = [];

 while ($row = $result->fetch_assoc()) {
    $userGoals[] = $row;
}

 $stmt->close();

 $conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">


  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <title>Add Goal</title>
  <style>
     body {
      display: flex;
      flex-direction: column;

      background-image: url('images/vecteezy_pink-pastel-color-abstract-background-design-and-soft-pastel_9279989.jpg');
      background-size: cover;
      background-position: center;
      margin: 0;
      padding: 0;
     
    }

    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

    .actualContainer {
      display:flex;
      justify-content: space-between;  
      padding-left: 250px; 
    }

    .container {
      margin-left: 70px;
      margin-top: 40px;
      width: 500px;
      
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      background-color: #fff;
      border: 1px solid rgb(182, 167, 167);
      
    }

    .active-goals-box{
      margin-top: 60px;
      margin-right: 200px;
      height:170px;
      width:270px;
      padding: 40px;
      padding-top: 30px;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      background-color: #fff;
      border: 1px solid rgb(182, 167, 167);
      font-size: 20px;
      text-align: center;
      color:rgb(145, 92, 121);

    }

    .active-btn{
      width:100px;
    }

    .active-btn.active {
     background-color: green;  
     color: white;  
    }


    .achieved-goals-box{
      margin-top: 60px;
      margin-right: 10px;
      height:170px;
      width:270px;
      padding: 40px;
      padding-top: 30px;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      background-color: #fff;
      border: 1px solid rgb(182, 167, 167);
      text-align: center;
      font-size: 20px;
      color:rgb(145, 92, 121);


    }

    .done-btn {
      background-color: plum;  
      color: white;
      margin-top: 10px;
      width:100px;
    }

    .AA-goal-box{
      display: flex;  
      flex-direction: column;  
      align-items: flex-start;
    }

   

    



    
    .usergoals {
      margin-left: 300px;
      margin-top: 35px;
      color: rgb(145, 92, 121);
      font-size: 30px;
    }
  


    .swiper-container {
      max-width: 80%;
    overflow-x: hidden;  
    white-space: nowrap;
    margin-left: 300px; 
    margin-top: 20px;
    margin-bottom: 100px;
    display: flex;
    position: relative;
    overflow: hidden;
    z-index: 1; 
   }
    
    
    
    

    .swiper-wrapper {
      display: flex;
      
    }
    .swiper-slide {
      flex: 0 0 auto;
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


    .goal-box {
       
      width: 270px;
  height: 200px;
  border-radius: 10px;
  background-color: #fff;
  border: 1px solid #ddd;
  box-shadow: 0 2px 4px grey;
  overflow: hidden;  
  position: relative;
    }

    .goal-box h3 {
      margin-bottom: 10px;
      color: #333;
    }

    .goal-box p {
      margin-bottom: 5px;
    }


    .color-box {
  height: 25px;
  background-color: inherit;  
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  border-radius: 10px 10px 0 0; 
}

.goal-details {
  padding: 35px;
  padding-bottom: 0px;
  justify-content: center;
}

.btn-container{
  justify-content: center;
  padding-left: 35px;
  padding-right: 35px;
  padding-top: 5px;
}

 




    




    .addgoals {
      text-align: center;
      color: rgb(145, 92, 121);
      font-size: 30px;
    }

    label {
      display: block;
      margin-bottom: 10px;
    }

    input[type="text"],
    input[type="time"],
    input[type="date"],
    input[type="checkbox"],
    button {
      width: 100%;
      padding: 8px;
      margin-bottom: 10px;
      box-sizing: border-box;
    }

    .startdate {
      display: flex;
      gap: 10px;
      margin-bottom: 10px;
    }

    .startdate label {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 30px;
      height: 30px;
      border-radius: 50%;
      border: 1px solid #ccc;
      cursor: pointer;
    }

    

    button {
      background-color: rgb(145, 92, 121);
       color: #fff;
      border: none;
      cursor: pointer;
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
    .userprof{
      margin-left: 20px;
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
    z-index: 2; 
    margin-left:260px;
    
}


footer p {
    margin: 0;
    font-size: 14px;
    text-align: center;
    margin-right: 200px;
}

.a1{
  font-size: 25px;
}
.a2{
  font-size: 25px;
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

  <div class="actualContainer">
    <div class="container">
      <h2 class="addgoals">Add Goal</h2>
      <form id="addGoalForm" action="goal1.php" method="POST">
  
        <label for="goalName">Goal Name:</label>
        <input type="text" id="goalName" name="goalName" required>
  
        <label for="goalColor">Choose Color:</label>
        <input type="color" id="color" name="color" required>

        <label for="dueDate">Due Date:</label>
        <input type="date" id="dueDate" name="dueDate" required><br><br>

        <button type="submit">Add Goal</button>

      </form>
    

    </div>
    <div class="AA-goal-box">
      <div class="active-goals-box">
        <div class="a1">
          <h2>Active Goals</h2>
          <p id="activeGoalsCount">0</p>
        </div>
      </div>
      <div class="achieved-goals-box">
        <div class="a2">
          <h2>Achieved Goals</h2>
          <p id="achievedGoalsCount"><?php echo $_SESSION['achieved_goals_count']; ?></p>
        </div>
      </div>
    </div>

  </div>

    <h2 class="usergoals">User Goals</h2>


    
    <div class="swiper-container">
      <div class="swiper-wrapper">
        <?php foreach ($userGoals as $goal) : ?>
          <div class="swiper-slide">
            <div class="goal-box">
              <div class="color-box" style="background-color: <?php echo htmlspecialchars($goal['color']); ?>"></div>
              <div class="goal-details">           
                <h3><?php echo htmlspecialchars($goal['goal_name']); ?></h3>
                <p><strong>Due Date:</strong> <?php echo htmlspecialchars($goal['due_date']); ?></p>
              </div>
           
              <div class="btn-container">
                <button class="active-btn" data-goal-id="<?php echo $goal['id']; ?>">Active</button>
                <button class="done-btn" data-goal-id="<?php echo $goal['id']; ?>">Done!</button>
              </div>
                 
              
              
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>
    
  </div>
  
  



  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var swiper = new Swiper('.swiper-container', {
        slidesPerView: '4',  
        spaceBetween: 10,  
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
      });
    });


     
  document.addEventListener('DOMContentLoaded', function () {
     const activeBtns = document.querySelectorAll('.active-btn');
    activeBtns.forEach(btn => {
      const isActive = localStorage.getItem(btn.dataset.goalId) === 'true';
      if (isActive) {
        btn.classList.add('active');
      }
    });

     document.querySelectorAll('.active-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        this.classList.toggle('active');
        const isActive = this.classList.contains('active');
        localStorage.setItem(this.dataset.goalId, isActive ? 'true' : 'false');
        updateActiveGoalsCount();
      });
    });

     function updateActiveGoalsCount() {
      const activeBtns = document.querySelectorAll('.active-btn.active');
      const activeGoalsCount = activeBtns.length;
      document.getElementById('activeGoalsCount').textContent = activeGoalsCount;
    }

     updateActiveGoalsCount();
  });


  document.addEventListener('DOMContentLoaded', function () {
       var today = new Date().toISOString().split('T')[0];
      
       document.getElementById('dueDate').setAttribute('min', today);
    });





  
    document.addEventListener('DOMContentLoaded', function () {
     const userId = <?php echo $_SESSION['user_id']; ?>;
    const achievedCount = parseInt(localStorage.getItem(`achievedCount_${userId}`)) || 0;
    document.getElementById('achievedGoalsCount').textContent = achievedCount;

     document.querySelectorAll('.done-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const goalId = this.dataset.goalId;
            
             
            deleteGoal(goalId);

             
            localStorage.setItem(`achievedCount_${userId}`, achievedCount + 1);
            document.getElementById('achievedGoalsCount').textContent = achievedCount + 1;

             
            this.closest('.swiper-slide').remove();
        });
    });

    // Function to send AJAX request to delete goal
    function deleteGoal(goalId) {
        // Create an XMLHttpRequest  
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log('Goal deleted successfully.');
                } else {
                    console.error('Error deleting goal.');
                }
            }
        };

        // Send a POST request to delete_goal.php with the goal ID
        xhr.open('POST', 'delete_goal.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send(`goalId=${goalId}`);
    }
});


  </script>


</body>
</html>