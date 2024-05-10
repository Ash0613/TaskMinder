<?php
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];  
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

     if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($phone)) {
        echo "All fields are required.";
        exit();
    }

    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

     $servername = "localhost:3308";  
    $username_db = "root";  
    $password_db = "";  
    $dbname = "task_manager";

     $conn = new mysqli($servername, $username_db, $password_db, $dbname);

     if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

     $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $phone, $hashed_password);

     if ($stmt->execute()) {
        header("Location: login.html");
    } else {
        echo "Error: " . $stmt->error;
    }

     $stmt->close();
    $conn->close();
}
?>
