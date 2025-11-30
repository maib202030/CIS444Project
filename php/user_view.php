<?php
require "../admin_auth.php";
require "../db.php";

$result = $conn->query("SELECT userId, name, email, role FROM User");
?>

<table border="1">
<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['userId'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><?= $row['role'] ?></td>
    <td>
        <a href="edit_user.php?id=<?= $row['userId'] ?>">Edit</a>
        <a href="delete_user.php?id=<?= $row['userId'] ?>">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
