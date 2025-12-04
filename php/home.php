<?php
// home.php
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: login.html");
    exit;
}
$userName = $_SESSION['name'] ?? "User";

// Load HTML file as text
$html = file_get_contents("../html/home.html");

// Replace [User] placeholder
$html = str_replace("[User]", htmlspecialchars($userName), $html);

// Output the modified HTML
echo $html;
exit;