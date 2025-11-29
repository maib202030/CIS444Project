<?php
require 'db.php';

$userId = intval($_POST['userId'] ?? null);
$title = $_POST['title'] ?? 'New Portfolio';
$description = $_POST['description'] ?? '';
$theme = $_POST['theme'] ?? 'default';

// Create portfolio
$stmt = $conn->prepare("INSERT INTO Portfolio (userId, title, description, theme, isPublished) VALUES (?, ?, ?, ?, FALSE)");
$stmt->bind_param("isss", $userId, $title, $description, $theme);
$stmt->execute();

$newPortfolioId = $conn->insert_id;

// Create default AboutMe
$stmt = $conn->prepare("INSERT INTO AboutMe (portfolioId, biography, tagline) VALUES (?, 'Tell us about yourself…', 'Your Professional tagline')");
$stmt->bind_param("i", $newPortfolioId);
$stmt->execute();

echo json_encode(['newPortfolioId' => $newPortfolioId]);
?>
