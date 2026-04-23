<?php
require_once 'include/auth.php';
require_once 'include/navbar.php';
require_once 'config/config.php';

$stmt = mysqli_query($link, "SELECT * FROM users");
$users = $stmt->fetch_all(MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panel.css">
    <title>Panel Admin</title>
</head>
<body>

<div class="tickets-container">
    <table class="tickets-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Password</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr data-id="<?= $u['id'] ?>">
                <td><?= htmlspecialchars($u['id']) ?></td>

                <td>
                    <span class="cell-text"><?= htmlspecialchars($u['nom']) ?></span>
                    <input class="cell-input" name="nom" type="text" value="<?= htmlspecialchars($u['nom']) ?>">
                </td>

                <td>
                    <span class="cell-text"><?= htmlspecialchars($u['email']) ?></span>
                    <input class="cell-input" name="email" type="email" value="<?= htmlspecialchars($u['email']) ?>">
                </td>

                <td>
                    <span class="cell-text"><?= htmlspecialchars($u['role']) ?></span>
                    <select class="cell-select" name="role">
                        <option value="admin"   <?= $u['role']==='admin'   ? 'selected':'' ?>>Admin</option>
                        <option value="technicien" <?= $u['role']==='technicien' ? 'selected':'' ?>>Technicien</option>
                        <option value="user"    <?= $u['role']==='user'    ? 'selected':'' ?>>User</option>
                    </select>
                </td>
                <td>
                    <span class="cell-text"><?= htmlspecialchars($u['mot_de_passe']) ?>
                </span>
                    <input class="cell-input" name="mot_de_passe" type="text" value="<?= htmlspecialchars($u['mot_de_passe']) ?>">
                </td>

                <td><button class="btn-action edit">Edit</button></td>
                <td><button class="btn-action delete">Delete</button></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>