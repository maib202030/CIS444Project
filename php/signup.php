<?php
require "db.php"; //change later
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm']);

    if ($password !== $confirm) {
        die("Passwords do not match.");
    }
    if (strlen($password) < 6) {
        die("Password must be at least 6 characters long.");
    }

    //Check if user already exists
    $checkStmt = $conn->prepare("SELECT userId FROM User WHERE email = ?");
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        die("User already exists. Please log in.");
    }
    $checkStmt->close();

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO User (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $username, $hashed);

    if ($stmt->execute()) {
        // Redirect to login once sign up is done
        header("Location: ../html/login.html");
        exit;
    } else {
        die("Error creating user: " . $conn->error);
    }
}
?>
