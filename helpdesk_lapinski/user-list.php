<?php
require_once 'include/auth.php';
require_once 'include/navbar.php';
require_once 'config/config.php';

// Sauvegarde
if(isset($_POST['action']) && $_POST['action'] === 'save'){
    $id    = $_POST['id'];
    $nom   = $_POST['nom'];
    $email = $_POST['email'];
    $role  = $_POST['role'];
    $mdp   = $_POST['mot_de_passe'];

    if(!empty($mdp)){
        $hash = password_hash($mdp, PASSWORD_DEFAULT);
        $sql  = "UPDATE users SET nom=?, email=?, role=?, mot_de_passe=? WHERE id=?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $nom, $email, $role, $hash, $id);
    } else {
        $sql  = "UPDATE users SET nom=?, email=?, role=? WHERE id=?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $nom, $email, $role, $id);
    }
    mysqli_stmt_execute($stmt);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Suppression
if(isset($_POST['action']) && $_POST['action'] === 'delete'){
    $id   = $_POST['id'];
    $sql  = "DELETE FROM users WHERE id=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

$stmt  = mysqli_query($link, "SELECT * FROM users");
$users = mysqli_fetch_all($stmt, MYSQLI_ASSOC);

// ID en cours d'édition (si bouton Edit cliqué)
$edit_id = isset($_POST['edit_id']) ? (int)$_POST['edit_id'] : null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/user-list.css">
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
            <?php $editing = ($edit_id === (int)$u['id']); ?>
            <tr>
                <td><?= htmlspecialchars($u['id']) ?></td>

                <?php if($editing): ?>

                    <form method="POST" action="">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="id"     value="<?= $u['id'] ?>">

                    <td>
                        <input type="text" name="nom" value="<?= htmlspecialchars($u['nom']) ?>" required>
                    </td>
                    <td>
                        <input type="email" name="email" value="<?= htmlspecialchars($u['email']) ?>" required>
                    </td>
                    <td>
                        <select name="role">
                            <option value="admin"      <?= $u['role']==='admin'      ? 'selected':'' ?>>Admin</option>
                            <option value="technicien" <?= $u['role']==='technicien' ? 'selected':'' ?>>Technicien</option>
                            <option value="user"       <?= $u['role']==='user'       ? 'selected':'' ?>>User</option>
                        </select>
                    </td>
                    <td>
                        <!-- Champ password vide, placeholder pour indiquer optionnel -->
                        <div class="pass-wrapper">
                            <input type="password" name="mot_de_passe" id="mdp_<?= $u['id'] ?>" placeholder="Laisser vide = inchangé">
                        </div>
                    </td>
                    <td><button type="submit" class="btn-action save">Save</button></td>
                    </form>

                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id"     value="<?= $u['id'] ?>">
                            <button type="submit" class="btn-action delete"
                                onclick="return confirm('Supprimer cet utilisateur ?')">Delete</button>
                        </form>
                    </td>

                <?php else: ?>

                    <td><?= htmlspecialchars($u['nom']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['role']) ?></td>
                    <td>••••••••</td>

                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="edit_id" value="<?= $u['id'] ?>">
                            <button type="submit" class="btn-action edit">Edit</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id"     value="<?= $u['id'] ?>">
                            <button type="submit" class="btn-action delete"
                                onclick="return confirm('Supprimer cet utilisateur ?')">Delete</button>
                        </form>
                    </td>

                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>