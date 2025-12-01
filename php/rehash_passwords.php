<?php
require "db.php";

echo "<pre>";

$result = $conn->query("SELECT userId, password FROM User");

while ($row = $result->fetch_assoc()) {

    $id = $row['userId'];

    // REMOVE trailing spaces that broke detection earlier
    $plain = rtrim($row['password']);

    echo "User $id: '$plain'\n";

    // Detect real bcrypt hashes only
    if (strpos($plain, '$2') === 0) {
        echo " -> Already hashed, skipping\n\n";
        continue;
    }

    echo " -> Not hashed, converting...\n";

    // Hash and update
    $newHash = password_hash($plain, PASSWORD_DEFAULT);

    $update = $conn->prepare("UPDATE User SET password=? WHERE userId=?");
    $update->bind_param("si", $newHash, $id);
    $update->execute();

    echo " -> Updated! New hash stored.\n\n";
}

echo "Done!";
