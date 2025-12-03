<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require "db.php"; 
session_start();

header("Content-Type: application/json");

// Only handle POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

// Get POST data safely
$email = trim($_POST["email"] ?? "");
$newpass1 = trim($_POST["newpass1"] ?? "");
$newpass2 = trim($_POST["newpass2"] ?? "");

// Validate form data
if (empty($email) || empty($newpass1) || empty($newpass2)) {
    echo json_encode(["success" => false, "message" => "Please fill out all fields."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Invalid email format."]);
    exit;
}

if ($newpass1 !== $newpass2) {
    echo json_encode(["success" => false, "message" => "Passwords do not match."]);
    exit;
}

// Check if the user exists
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "No user found with that email."]);
    exit;
}

// Hash new password
$hashed = password_hash($newpass1, PASSWORD_DEFAULT);

// Update password
$update = "UPDATE users SET password = ? WHERE email = ?";
$stmt = $conn->prepare($update);
$stmt->bind_param("ss", $hashed, $email);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Password updated successfully!"]);
} else {
    echo json_encode(["success" => false, "message" => "Error updating password."]);
}

$conn->close();
?>
