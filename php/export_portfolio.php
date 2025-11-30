<?php
require 'db.php';

$portfolioId = intval($_GET['portfolioId'] ?? 0);
if (!$portfolioId) die("Invalid portfolio");

$stmt = $conn->prepare(
  "SELECT title, description, theme FROM Portfolio WHERE portfolioId=?"
);
$stmt->bind_param("i", $portfolioId);
$stmt->execute();
$portfolio = $stmt->get_result()->fetch_assoc();
if (!$portfolio) die("Portfolio not found");

header("Content-Type: text/html");
header("Content-Disposition: attachment; filename=\"portfolio_$portfolioId.html\"");

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title><?= htmlspecialchars($portfolio['title']) ?></title>
</head>
<body>
  <h1><?= htmlspecialchars($portfolio['title']) ?></h1>
  <p><?= htmlspecialchars($portfolio['description']) ?></p>
  <p>Theme: <?= htmlspecialchars($portfolio['theme']) ?></p>
</body>
</html>
