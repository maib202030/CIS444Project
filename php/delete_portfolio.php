<?php
header('Content-Type: application/json');
require 'db.php';

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);
$portfolioId = intval($data['portfolioId'] ?? 0);

if ($portfolioId <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid portfolio ID']);
    exit;
}

// Begin transaction
$conn->begin_transaction();

try {
    // Delete the portfolio (related projects/skills will be auto-deleted if FK ON DELETE CASCADE is set)
    $stmt = $conn->prepare("DELETE FROM Portfolio WHERE portfolioId = ?");
    $stmt->bind_param("i", $portfolioId);

    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }

    $stmt->close();
    $conn->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
