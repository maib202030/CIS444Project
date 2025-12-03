<?php
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['userId'];

$stmt = $conn->prepare("DELETE FROM users WHERE userId=?");
$stmt->bind_param("i", $userId);
$stmt->execute();

echo "OK";
?>
