<?php
require 'db.php';

$portfolioId = intval($_GET['portfolioId'] ?? 0);
if (!$portfolioId) die("Invalid portfolio");

// Fetch Portfolio data
$stmt = $conn->prepare("SELECT title, description FROM Portfolio WHERE portfolioId=?");
$stmt->bind_param("i", $portfolioId);
$stmt->execute();
$portfolio = $stmt->get_result()->fetch_assoc();
if (!$portfolio) die("Portfolio not found");

// Fetch About Me
$stmt = $conn->prepare("SELECT tagline, biography FROM AboutMe WHERE portfolioId=?");
$stmt->bind_param("i", $portfolioId);
$stmt->execute();
$about = $stmt->get_result()->fetch_assoc();

// Fetch Projects
$stmt = $conn->prepare("SELECT title, description, link FROM Projects WHERE portfolioId=? ORDER BY displayOrder ASC");
$stmt->bind_param("i", $portfolioId);
$stmt->execute();
$projects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch Skills
$stmt = $conn->prepare("SELECT skillName, category FROM Skill WHERE portfolioId=? ORDER BY displayOrder ASC");
$stmt->bind_param("i", $portfolioId);
$stmt->execute();
$skillsRaw = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$skills = [
    'key_skill' => [],
    'hard_skill' => [],
    'soft_skill' => []
];

foreach ($skillsRaw as $s) {
    if (isset($skills[$s['category']])) {
        $skills[$s['category']][] = $s['skillName'];
    }
}

// Fetch Resume
$stmt = $conn->prepare("SELECT fileName, fileType, filePath FROM Resume WHERE portfolioId=?");
$stmt->bind_param("i", $portfolioId);
$stmt->execute();
$resume = $stmt->get_result()->fetch_assoc();

header("Content-Type: text/html");
header("Content-Disposition: attachment; filename=\"portfolio_$portfolioId.html\"");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Portfolio</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    h1, h2, h3, h4 { margin: 10px 0; }
    .section { margin-bottom: 20px; }
    .project-card, .skills-section, .resume-section { margin-bottom: 10px; }
    a { color: #1a0dab; text-decoration: none; }
  </style>
</head>
<body>
  <h1>Portfolio</h1>

  <!-- About Me -->
  <div class="section">
    <h2>About Me</h2>
    <p><strong>Tagline:</strong> <?= htmlspecialchars($about['tagline'] ?? "") ?></p>
    <p><strong>Biography:</strong> <?= nl2br(htmlspecialchars($about['biography'] ?? "")) ?></p>
  </div>

  <!-- Projects -->
  <div class="section">
    <h2>Projects</h2>
    <?php if (!empty($projects)): ?>
      <?php foreach ($projects as $proj): ?>
        <div class="project-card">
          <h4><?= htmlspecialchars($proj['title'] ?? "") ?></h4>
          <p><?= nl2br(htmlspecialchars($proj['description'] ?? "")) ?></p>
        </div>
      <?php endforeach; ?>

      <?php
        // Show only the first project link after all projects
        $firstLink = null;
        foreach ($projects as $proj) {
            if (!empty($proj['link'])) {
                $firstLink = $proj['link'];
                break;
            }
        }
        if ($firstLink):
      ?>
        <p><a href="<?= htmlspecialchars($firstLink) ?>" target="_blank">Project Link</a></p>
      <?php endif; ?>

    <?php else: ?>
      <p>No projects added.</p>
    <?php endif; ?>
  </div>

  <!-- Skills -->
  <div class="section skills-section">
    <h2>Skills</h2>
    <p><strong>Key Skills:</strong> <?= htmlspecialchars(implode(", ", $skills['key_skill'])) ?></p>
    <p><strong>Hard Skills:</strong> <?= htmlspecialchars(implode(", ", $skills['hard_skill'])) ?></p>
    <p><strong>Soft Skills:</strong> <?= htmlspecialchars(implode(", ", $skills['soft_skill'])) ?></p>
  </div>

  <!-- Resume -->
  <div class="section resume-section">
    <h2>Resume</h2>
    <?php if (!empty($resume)): ?>
      <p><a href="<?= htmlspecialchars($resume['filePath']) ?>" target="_blank">Download Resume: <?= htmlspecialchars($resume['fileName']) ?></a></p>
    <?php else: ?>
      <p>No resume uploaded.</p>
    <?php endif; ?>
  </div>
</body>
</html>
