<?php
header('Content-Type: application/json');
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$portfolioId = $data['portfolioId'] ?? null;
$title = $data['projectTitle'] ?? '';
$description = $data['description'] ?? '';
$link = $data['projectLinks'] ?? '';

if (!$portfolioId || !$title || !$description || !$link) {
    echo json_encode(['success' => false, 'error' => 'Missing fields']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO Projects (portfolioId, title, description, link) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $portfolioId, $title, $description, $link);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'projectId' => $stmt->insert_id]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
$stmt->close();
$conn->close();
?>
