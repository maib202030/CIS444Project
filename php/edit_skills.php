<?php
header('Content-Type: application/json');
require 'db.php';

try {
    // Validate input
    if (!isset($_POST['skillId'], $_POST['skillName'])) {
        throw new Exception("Skill ID and skill name are required.");
    }

    $skillId = intval($_POST['skillId']);
    $skillName = trim($_POST['skillName']);

    if ($skillName === '') {
        throw new Exception("Skill name cannot be empty.");
    }

    // Prepare and execute statement
    $stmt = $conn->prepare("UPDATE Skill SET skillName = ? WHERE skillId = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("si", $skillName, $skillId);

    if (!$stmt->execute()) {
        throw new Exception("Execution failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'message' => 'Skill updated successfully!']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
