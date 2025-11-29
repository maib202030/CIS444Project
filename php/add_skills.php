<?php
header('Content-Type: application/json');
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

$portfolioId = $data['portfolioId'] ?? null;
$keySkills = $data['keyskills'] ?? '';
$hardSkills = $data['hardskills'] ?? '';
$softSkills = $data['softskills'] ?? '';

if (!$portfolioId) {
    echo json_encode(['success' => false, 'error' => 'Portfolio ID missing']);
    exit;
}

try {
    $errors = [];

    // Insert key skills
    if ($keySkills) {
        $stmt = $conn->prepare("INSERT INTO Skill (portfolioId, skillName, category) VALUES (?, ?, 'key_skill')");
        $stmt->bind_param("is", $portfolioId, $keySkills);
        if (!$stmt->execute()) $errors[] = $stmt->error;
        $stmt->close();
    }

    // Insert hard skills
    if ($hardSkills) {
        $stmt = $conn->prepare("INSERT INTO Skill (portfolioId, skillName, category) VALUES (?, ?, 'hard_skill')");
        $stmt->bind_param("is", $portfolioId, $hardSkills);
        if (!$stmt->execute()) $errors[] = $stmt->error;
        $stmt->close();
    }

    // Insert soft skills
    if ($softSkills) {
        $stmt = $conn->prepare("INSERT INTO Skill (portfolioId, skillName, category) VALUES (?, ?, 'soft_skill')");
        $stmt->bind_param("is", $portfolioId, $softSkills);
        if (!$stmt->execute()) $errors[] = $stmt->error;
        $stmt->close();
    }

    if ($errors) {
        echo json_encode(['success' => false, 'error' => implode('; ', $errors)]);
    } else {
        echo json_encode(['success' => true]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
