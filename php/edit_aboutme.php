<?php
header('Content-Type: application/json');
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$portfolioId = $data['portfolioId'] ?? null;
$tagline = $data['tagline'] ?? '';
$bio = $data['bio'] ?? '';

if (!$portfolioId || !$tagline || !$bio) {
    echo json_encode(['success' => false, 'error' => 'Missing fields']);
    exit;
}

$stmt = $conn->prepare("UPDATE AboutMe SET tagline = ?, biography = ? WHERE portfolioId = ?");
$stmt->bind_param("ssi", $tagline, $bio, $portfolioId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
$stmt->close();
$conn->close();
?>
