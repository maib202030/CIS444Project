<?php

require "db.php";

$result = $conn->query("SELECT userId, name, email, role FROM users ORDER BY userId");

$users = [];

while ($row = $result->fetch_assoc()) {

    $users[] = $row;

}

echo json_encode($users);

?>