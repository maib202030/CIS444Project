<?php
header('Content-Type: application/json');
require 'db.php';

$portfolioId = intval($_POST['portfolioId'] ?? 0);

if (!$portfolioId || !isset($_FILES['resume'])) {
    echo json_encode(['success' => false, 'error' => 'Missing portfolio ID or file']);
    exit;
}

$file = $_FILES['resume'];
$allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
$fileType = $file['type'];
$fileSize = $file['size'];

if (!in_array($fileType, $allowedTypes)) {
    echo json_encode(['success' => false, 'error' => 'Invalid file type']);
    exit;
}

$uploadDir = '../uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

$fileName = basename($file['name']);
$filePath = $uploadDir . time() . '_' . $fileName;

if (move_uploaded_file($file['tmp_name'], $filePath)) {
    // Save to DB
    $stmt = $conn->prepare("INSERT INTO Resume (portfolioId, fileName, fileType, filePath, fileSize) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE fileName=?, fileType=?, filePath=?, fileSize=?, uploadDate=NOW()");
    $stmt->bind_param(
        "isssisssi",
        $portfolioId,
        $fileName,
        $fileType,
        $filePath,
        $fileSize,
        $fileName,
        $fileType,
        $filePath,
        $fileSize
    );
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'fileName' => $fileName]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Upload failed']);
}

$conn->close();
?>
