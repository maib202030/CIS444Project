<?php
header('Content-Type: application/json');
require 'db.php';

$portfolioId = intval($_GET['portfolioId'] ?? 0);

if (!$portfolioId) {
    echo json_encode(['exists' => false]);
    exit;
}

$stmt = $conn->prepare("SELECT fileName, filePath, fileType, uploadDate FROM Resume WHERE portfolioId=? LIMIT 1");
$stmt->bind_param("i", $portfolioId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'exists' => true,
        'fileName' => $row['fileName'],
        'filePath' => $row['filePath'],
        'fileType' => $row['fileType'],
        'uploadDate' => $row['uploadDate']
    ]);
} else {
    echo json_encode(['exists' => false]);
}

$stmt->close();
$conn->close();
?>
