<?php
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: login.html");
    exit;
}

header("Location: home.html");
exit;
?>