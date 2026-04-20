<?php
require_once 'include/auth.php';
require_once 'include/navbar.php';
require_once 'config/config.php';

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
    "SELECT * FROM tickets  ORDER BY cree_le DESC"
)->fetch_all(MYSQLI_ASSOC);

$selected_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Ticket actif
$ticket_actif = null;
$messages = [];

// ticket selectionné par l'user
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

// modif statut ticket 
if (isset($_POST['update_statut'])) {
    // var_dump($_POST['update_statut']);
    // if (isset($_POST['statut'])) {
        $stmt = mysqli_prepare($link, "UPDATE tickets SET statut = ? WHERE ticket_id = ?");
        mysqli_stmt_bind_param($stmt, "si", $_POST['statut'], $_POST['ticket_id_statut']);
        mysqli_stmt_execute($stmt);
    // }
    header("Location: ?id=" . (int)$_POST['ticket_id_statut']);
    exit;
}

// Pagination
$par_page = 10;
$page_actuelle = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page_actuelle - 1) * $par_page;

// Nombre total de tickets
$total = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) as total FROM tickets"))['total'];
$total_pages = ceil($total / $par_page);

// Liste tickets avec limite
$stmt = mysqli_prepare($link, "SELECT * FROM tickets ORDER BY cree_le DESC LIMIT ? OFFSET ?");
mysqli_stmt_bind_param($stmt, "ii", $par_page, $offset);
mysqli_stmt_execute($stmt);
$tickets = mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Helpdesk</title>
<link rel="stylesheet" href="css/historique.css">
</head>
<body>

<!-- MAIN CHAT -->
<div class="modif-ticket">

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-header">TICKETS</div>

    <div class="sidebar-list">
        <?php foreach ($tickets as $t): ?>
            <a href="?id=<?= $t['id'] ?>&page=<?= $page_actuelle ?>"
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

    <!-- PAGINATION -->
    <?php if ($total_pages > 1): ?>
    <nav class="sidebar-pagination" aria-label="Pagination tickets">
        <ul class="pagination">

            <!-- Précédent -->
            <li class="page-item <?= $page_actuelle <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page_actuelle - 1 ?><?= $selected_id ? '&id='.$selected_id : '' ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <!-- Numéros de pages -->
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $page_actuelle ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?><?= $selected_id ? '&id='.$selected_id : '' ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>

            <!-- Suivant -->
            <li class="page-item <?= $page_actuelle >= $total_pages ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page_actuelle + 1 ?><?= $selected_id ? '&id='.$selected_id : '' ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>

        </ul>
    </nav>
    <?php endif; ?>
    </div>

    <!-- CHAT -->
    <div class="main">

        <?php if ($selected_id && $ticket_actif): ?>

            <div class="main-header">
                    <div class="main-header-left">
                       <h2><?= htmlspecialchars($ticket_actif['titre']) ?></h2>
                       <p><?= htmlspecialchars($ticket_actif['description']) ?></p>
             </div>

                 <form method="POST" class="statut-form">
                <input type="hidden" name="ticket_id_statut" value="<?= $selected_id ?>">
                  <select name="statut" class="form-select" onchange="this.form.submit()">
                        <option value="ouvert"   <?= $ticket_actif['statut'] === 'ouvert'   ? 'selected' : '' ?>>🟢 Ouvert</option>
                        <option value="en_cours" <?= $ticket_actif['statut'] === 'en_cours' ? 'selected' : '' ?>>🟡 En cours</option>
                       <option value="ferme"    <?= $ticket_actif['statut'] === 'ferme'    ? 'selected' : '' ?>>🔴 Fermé</option>
                 </select>
                 <button type="submit" name="update_statut" hidden></button>
                 </form>
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