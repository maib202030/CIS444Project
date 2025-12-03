<?php
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$userId = $data['userId'];
$field  = $data['field'];
$value  = $data['value'];

// Whitelist allowed fields to prevent SQL injection
$allowed = ['name', 'email', 'role'];
if (!in_array($field, $allowed)) {
    die("Invalid field");
}

$stmt = $conn->prepare("UPDATE users SET $field=? WHERE userId=?");
$stmt->bind_param("si", $value, $userId);
$stmt->execute();

echo "OK";
?>
