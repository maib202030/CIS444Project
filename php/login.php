<?php

// DB Connection
$host = "localhost";
$user = "group6";      
$pass = "wn04pe18";          
$dbname = "group6";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// 2. Read form input
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// 3. Check for empty fields
if (empty($email) || empty($password)) {
    die("Email or password is empty. <br>Email: '$email' Password: '$password'");
}

// 4. Prepare statement
$stmt = $conn->prepare("SELECT password FROM user WHERE email = ?");
if (!$stmt) {
    die("Prepared statement failed: " . $conn->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

// 5. Check if user exists
if ($stmt->num_rows === 0) {
    header("Location: ../html/login_failed.html");
}

// 6. Fetch password from DB
$stmt->bind_result($dbPassword);
$stmt->fetch();

/*
// Debug output
echo "Entered password: '$password'<br>";
echo "Password in DB: '$dbPassword'<br>";
*/

// 7. Compare passwords
if ($password === $dbPassword) {
    //echo "Login successful!";
    header("Location: ../html/home.html");
    exit;
} else {
    header("Location: ../html/login_failed.html");
}

?>
