<?php
/*
This entire file, as of right now, is used for testing.

Used in accordance with XAMPP, with Apache and MySQL servers activated,
and a database specifically named "fake_db" in phpMyAdmin.

This mimics the sql database used in the school servers.
*/

$host = "localhost";
$user = "root";        // default for XAMPP
$pass = "";            // default for XAMPP
$db   = "fake_db";     // actual database name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    // Do NOT echo anything here.
    http_response_code(500);
    throw new Exception("Database connection failed: " . $conn->connect_error);
}
?>