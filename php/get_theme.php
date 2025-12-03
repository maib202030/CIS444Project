<?php
header("Content-Type: application/json");
require "db.php";

/*
  Temp: Simulated logged-in user; later this will come from $_SESSION["userId"]
*/
$loggedInUserId = 2;

$sql = "SELECT preference FROM users WHERE userId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $loggedInUserId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "preference" => $row["preference"]
    ]);
} else {
    echo json_encode([
        "preference" => "light"
    ]);
}

$stmt->close();
$conn->close();
?>
