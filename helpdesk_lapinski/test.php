<?php
require_once 'include/auth.php';
require_once 'include/navbar.php';
require_once 'config/config.php';

// Rôle user
$role_stmt = mysqli_query($link, "SELECT role FROM users WHERE id = " . $_SESSION['id_users']);
$role = mysqli_fetch_assoc($role_stmt)['role'];

// Créer ticket
if (isset($_POST['envoyer'])) {
    if (!empty($_POST['titre']) && !empty($_POST['description'])) {

        $stmt = mysqli_prepare($link, "INSERT INTO tickets (titre, description, cree_le, statut, user_id) VALUES (?, ?, NOW(), 'ouvert', ?)");
        mysqli_stmt_bind_param($stmt, "ssi", $_POST['titre'], $_POST['description'], $_SESSION['id_users']);
        mysqli_stmt_execute($stmt);

        $new_id = mysqli_insert_id($link);

        $stmt2 = mysqli_prepare($link, "INSERT INTO messages (ticket_id, user_id, message, cree_le) VALUES (?, ?, ?, NOW())");
        mysqli_stmt_bind_param($stmt2, "iis", $new_id, $_SESSION['id_users'], $_POST['description']);
        mysqli_stmt_execute($stmt2);
    }
}

// Envoyer message
if (isset($_POST['send_message']) && !empty($_POST['message'])) {
    $stmt = mysqli_prepare($link, "INSERT INTO messages (ticket_id, user_id, message, cree_le) VALUES (?, ?, ?, NOW())");
    mysqli_stmt_bind_param($stmt, "iis", $_POST['ticket_id'], $_SESSION['id_users'], $_POST['message']);
    mysqli_stmt_execute($stmt);

    header("Location: ?id=" . (int)$_POST['ticket_id']);
    exit;
}

// Liste tickets
$tickets = mysqli_query(
    $link,
    "SELECT * FROM tickets WHERE user_id = " . (int)$_SESSION['id_users'] . " ORDER BY cree_le DESC"
)->fetch_all(MYSQLI_ASSOC);

$selected_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Ticket actif
$ticket_actif = null;
$messages = [];

if ($selected_id) {
    $stmt = mysqli_prepare($link, "SELECT * FROM tickets WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $selected_id);
    mysqli_stmt_execute($stmt);
    $ticket_actif = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    $stmt = mysqli_prepare($link, "SELECT * FROM messages WHERE ticket_id = ? ORDER BY id ASC");
    mysqli_stmt_bind_param($stmt, "i", $selected_id);
    mysqli_stmt_execute($stmt);
    $messages = mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Helpdesk</title>
<link rel="stylesheet" href="css/index.css">
</head>
<body>

<!-- FORM CREATE TICKET -->
<form method="POST" class="ticket-form">
    <div class="field">
        <label>Titre</label>
        <input type="text" name="titre" required>
    </div>
    <div class="field">
        <label>Description</label>
        <input type="text" name="description" required>
    </div>
    <button name="envoyer" class="btn-primary">Créer ticket</button>
</form>

<!-- MAIN CHAT -->
<div class="modif-ticket">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-header">TICKETS</div>

        <?php foreach ($tickets as $t): ?>
            <a href="?id=<?= $t['id'] ?>"
               class="sidebar-item <?= $selected_id == $t['id'] ? 'active' : '' ?>">

                <div class="sidebar-item-title">
                    <?= htmlspecialchars($t['titre']) ?>
                </div>

                <div class="sidebar-item-status-nom">
                    <?= htmlspecialchars($t['statut']) ?>
                </div>

            </a>
        <?php endforeach; ?>
    </div>

    <!-- CHAT -->
    <div class="main">

        <?php if ($selected_id && $ticket_actif): ?>

            <div class="main-header">
                <h2><?= htmlspecialchars($ticket_actif['titre']) ?></h2>
                <p><?= htmlspecialchars($ticket_actif['description']) ?></p>
            </div>

            <!-- MESSAGES -->
            <div class="historique">

                <?php foreach ($messages as $msg): ?>
                    <div class="msg">

                        <div class="avatar">
                            <?= strtoupper(substr($msg['user_id'], 0, 1)) ?>
                        </div>

                        <div class="contenu">

                            <div class="meta">
                                <span class="user">User <?= htmlspecialchars($msg['user_id']) ?></span>
                                <span class="heure">
                                    <?= date('H:i', strtotime($msg['cree_le'])) ?>
                                </span>
                            </div>

                            <div class="bulle">
                                <?= htmlspecialchars($msg['message']) ?>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

            <!-- INPUT -->
            <form method="POST" class="formulaire">
                <input type="hidden" name="ticket_id" value="<?= $selected_id ?>">
                <input type="text" name="message" placeholder="Écrire un message..." required>
                <button name="send_message">Envoyer</button>
            </form>

        <?php else: ?>
            <div class="vide">Sélectionne un ticket 👈</div>
        <?php endif; ?>

    </div>
</div>

<script>
const histo = document.querySelector('.historique');
if (histo) histo.scrollTop = histo.scrollHeight;
</script>

</body>
</html>