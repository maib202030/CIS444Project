<?php
header("Content-Type: application/json");
require "db.php";

$request = json_decode(file_get_contents("php://input"), true);

if (!isset($request["preference"])) {
    echo json_encode(["error" => "No preference sent"]);
    exit();
}

$newTheme = $request["preference"];

/*
  Temp: Simulated logged-in user; later this will come from $_SESSION["userId"]
*/
$loggedInUserId = 2;

$sql = "UPDATE User 
        SET preference = ?, updatedAt = NOW() 
        WHERE userId = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $newTheme, $loggedInUserId);
$stmt->execute();

$stmt->close();
$conn->close();

echo json_encode([
    "status" => "success",
    "savedTheme" => $newTheme
]);
?>
