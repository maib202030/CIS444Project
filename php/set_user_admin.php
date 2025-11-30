<?php
session_start();
require "db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['userId'])) {
    echo json_encode([
        "success" => false,
        "message" => "Not logged in."
    ]);
    exit;
}

$enteredKey = $_POST['adminKey'] ?? "";

$MASTER_ADMIN_KEY = "MASTER-ADMIN-KEY";

if ($enteredKey === $MASTER_ADMIN_KEY) {

    $stmt = $conn->prepare("UPDATE User SET role='admin' WHERE userId=?");
    $stmt->bind_param("i", $_SESSION['userId']);
    $stmt->execute();

    $_SESSION['role'] = "admin";

    echo json_encode([
        "success" => true,
        "message" => "Admin privileges granted."
    ]);
    exit;
}

echo json_encode([
    "success" => false,
    "message" => "Invalid admin key."
]);
exit;
