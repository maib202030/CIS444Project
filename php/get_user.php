<?php
header("Content-Type: application/json");
require_once "db.php";
session_start();

if (empty($_SESSION['userId'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "error" => "Not logged in"]);
    exit;
}

$userId = intval($_SESSION['userId']);

$stmt = $conn->prepare("SELECT userId, name, email, profilePicture FROM users WHERE userId = ? LIMIT 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

if (!$user) {
    http_response_code(404);
    echo json_encode(["success" => false, "error" => "User not found"]);
    exit;
}

echo json_encode(["success" => true, "user" => $user]);
?>