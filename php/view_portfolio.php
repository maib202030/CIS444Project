<?php
header('Content-Type: application/json');
require 'db.php';

try {
    $portfolioId = intval($_GET['portfolioId'] ?? null);
    if ($portfolioId <= 0) {
        throw new Exception("Invalid portfolio ID.");
    }

    // Portfolio info
    $stmt = $conn->prepare(
        "SELECT p.portfolioId, p.title, p.description, p.theme, u.name as ownerName, p.isPublished 
         FROM Portfolio p 
         JOIN users u ON p.userId = u.userId 
         WHERE p.portfolioId = ?"
    );
    if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
    $stmt->bind_param("i", $portfolioId);
    $stmt->execute();
    $portfolio = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$portfolio) throw new Exception("Portfolio not found.");

    // About Me
    $stmt = $conn->prepare("SELECT biography, tagline, profileImage FROM AboutMe WHERE portfolioId = ?");
    $stmt->bind_param("i", $portfolioId);
    $stmt->execute();
    $aboutMe = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Projects
    $stmt = $conn->prepare("SELECT projectId, title, description, link FROM Projects WHERE portfolioId = ? ORDER BY displayOrder");
    $stmt->bind_param("i", $portfolioId);
    $stmt->execute();
    $projects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Skills
    $stmt = $conn->prepare("SELECT skillName, category FROM Skill WHERE portfolioId = ? ORDER BY category, displayOrder");
    $stmt->bind_param("i", $portfolioId);
    $stmt->execute();
    $skills = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Social Links
    $stmt = $conn->prepare("SELECT platform, url FROM SocialLink WHERE portfolioId = ?");
    $stmt->bind_param("i", $portfolioId);
    $stmt->execute();
    $socialLinks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Return JSON
    echo json_encode([
        "success" => true,
        "portfolio" => $portfolio,
        "aboutMe" => $aboutMe,
        "projects" => $projects,
        "skills" => $skills,
        "socialLinks" => $socialLinks
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
$conn->close();
?>
