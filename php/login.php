<?php

// DB Connection
session_start();
require_once "db.php"; // DB connection file

// 2. Read form input
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// 3. Check for empty fields
if (empty($email) || empty($password)) {
    die("Email or password is empty. <br>Email: '$email' Password: '$password'");
}

// 4. Prepare statement
$stmt->bind_result($userId, $dbPassword, $role, $name);
if (!$stmt) {
    die("Prepared statement failed: " . $conn->error);
}

$stmt = $conn->prepare("SELECT userId, name, password, role FROM User WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($userId, $name, $dbPassword, $role);
$stmt->fetch();

// 5. Check if user exists
if ($stmt->num_rows === 0) {
    header("Location: ../html/login_failed.html");
    exit;
}

// 6. Fetch password from DB
$stmt->bind_result($userId, $dbPassword);
$stmt->fetch();

/*
// Debug output
echo "Entered password: '$password'<br>";
echo "Password in DB: '$dbPassword'<br>";
*/

// 7. Compare passwords
if (password_verify($password, $dbPassword)) {
    $_SESSION['role'] = $role;
    $_SESSION['userId'] = $userId;
    $_SESSION['email']  = $email;
    $_SESSION['name']   = $name;   
    //echo "Login successful!";
    header("Location: ../html/home.html");
    exit;
} else {
    header("Location: ../html/login_failed.html");
    exit;
}

?>
