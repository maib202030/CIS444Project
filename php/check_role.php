<?php
session_start();
echo json_encode([
    "role" => $_SESSION['role'] ?? "user",
    "userId" => $_SESSION['userId'] ?? null,
    "name" => $_SESSION['name'] ?? null
]);
?>
