<?php
header('Content-Type: application/json');
require 'db.php';

try {
    $projectId = isset($_POST['projectId']) ? intval($_POST['projectId']) : null;
    $portfolioId = isset($_POST['portfolioId']) ? intval($_POST['portfolioId']) : null;

    if (!$projectId || !$portfolioId) {
        throw new Exception("Missing projectId or portfolioId.");
    }

    $stmt = $conn->prepare("DELETE FROM Projects WHERE projectId = ? AND portfolioId = ?");
    $stmt->bind_param("ii", $projectId, $portfolioId);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'No project found to delete.'
        ]);
    } else {
        echo json_encode(['success' => true]);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
