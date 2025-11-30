<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../php/login.php");
    exit();
}
?>
