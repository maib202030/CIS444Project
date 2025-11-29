<?php
header('Content-Type: application/json');
require 'db.php';

try {
    // Validate input
    if (!isset($_POST['portfolioId'], $_POST['title'])) {
        throw new Exception("Portfolio ID and title are required.");
    }

    $portfolioId = intval($_POST['portfolioId']);
    $title = trim($_POST['title']);

    if ($title === '') {
        throw new Exception("Title cannot be empty.");
    }

    // Prepare and execute statement
    $stmt = $conn->prepare("UPDATE Portfolio SET title = ?, lastModified = NOW() WHERE portfolioId = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("si", $title, $portfolioId);

    if (!$stmt->execute()) {
        throw new Exception("Execution failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'message' => 'Portfolio updated successfully!']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
