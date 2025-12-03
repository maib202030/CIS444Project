<?php
require "db.php";

$id    = $_POST['id'];
$name  = $_POST['name'];
$email = $_POST['email'];
$role  = $_POST['role'];

$stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=? WHERE userId=?");
$stmt->bind_param("sssi", $name, $email, $role, $id);
$stmt->execute();

echo "OK";
?>
