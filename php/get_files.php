<?php
session_start();
header("Content-Type: application/json");
require "db.php";

try {
    if (!isset($_SESSION['userId'])) {
        throw new Exception("Not logged in.");
    }

    $userId = intval($_SESSION['userId']);

    $stmt = $conn->prepare("
        SELECT portfolioId, title, description, createdDate
        FROM Portfolio
        WHERE userId = ?
        ORDER BY createdDate DESC
    ");

    if (!$stmt) throw new Exception($conn->error);

    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();
    $files = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        "success" => true,
        "files" => $files
    ]);

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
?>