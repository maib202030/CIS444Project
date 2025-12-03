<?php
session_start();
header('Content-Type: application/json'); // force JSON response
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING); // hide notices/warnings from breaking JSON
require 'db.php';

try {
    if (!isset($_SESSION['userId'])) {
        throw new Exception('User not logged in');
    }

    $userId = intval($_SESSION['userId']);
    $title = $_POST['title'] ?? 'New Portfolio';
    $description = $_POST['description'] ?? '';
    $theme = $_POST['theme'] ?? 'default';

    // Create portfolio
    $stmt = $conn->prepare("INSERT INTO Portfolio (userId, title, description, theme, isPublished) VALUES (?, ?, ?, ?, FALSE)");
    if (!$stmt) throw new Exception($conn->error);
    $stmt->bind_param("isss", $userId, $title, $description, $theme);
    if (!$stmt->execute()) throw new Exception($stmt->error);

    $newPortfolioId = $conn->insert_id;

    // Create default AboutMe
    $stmt = $conn->prepare("INSERT INTO AboutMe (portfolioId, biography, tagline) VALUES (?, 'Tell us about yourself…', 'Your Professional tagline')");
    if (!$stmt) throw new Exception($conn->error);
    $stmt->bind_param("i", $newPortfolioId);
    if (!$stmt->execute()) throw new Exception($stmt->error);

    echo json_encode(['newPortfolioId' => $newPortfolioId]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
