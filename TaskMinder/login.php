<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Get form data
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['pswd'];

 
     $servername = "localhost:3308";  
    $username_db = "root";  
    $password_db = "";  
    $dbname = "task_manager";  

     $conn = new mysqli($servername, $username_db, $password_db, $dbname);

     if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to fetch user details based on email or phone number
    $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE email = ? OR phone = ?");
    
    // Bind parameters
    $stmt->bind_param("ss", $email, $phone);

     $stmt->execute();

     $result = $stmt->get_result();

    // Check if a matching user is found
    if ($result->num_rows > 0) {
        // User found, verify password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password matches, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            require_once "send.php";

             header("Location: home.php");  
            exit();  
        } else {
             echo "Invalid password";
        }
    } else {
         echo "User not found";
    }

     $stmt->close();
    $conn->close();
}
?>
