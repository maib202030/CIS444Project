<?php
error_reporting(E_ALL);
ini_set('display_errors', 0); 
ini_set('log_errors', 1);      
ob_start();

header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

$portfolioId = intval($data['portfolioId'] ?? 0);
if ($portfolioId <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid portfolio ID']);
    exit;
}

function insertSkills($conn, $portfolioId, $skillsText, $category) {
    if (!$skillsText) return [];

    $errors = [];
    $skills = array_filter(array_map('trim', explode(',', $skillsText)));

    $stmt = $conn->prepare(
        "INSERT INTO Skill (portfolioId, skillName, category)
         VALUES (?, ?, ?)"
    );

    foreach ($skills as $skill) {
        $stmt->bind_param("iss", $portfolioId, $skill, $category);
        if (!$stmt->execute()) {
            $errors[] = $stmt->error;
        }
    }

    $stmt->close();
    return $errors;
}

$errors = [];

$errors = array_merge(
    $errors,
    insertSkills($conn, $portfolioId, $data['keyskills'] ?? '', 'key_skill'),
    insertSkills($conn, $portfolioId, $data['hardskills'] ?? '', 'hard_skill'),
    insertSkills($conn, $portfolioId, $data['softskills'] ?? '', 'soft_skill')
);

if ($errors) {
    echo json_encode(['success' => false, 'error' => implode('; ', $errors)]);
} else {
    echo json_encode(['success' => true]);
}

$conn->close();
?>