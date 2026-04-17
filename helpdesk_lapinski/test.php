<?php
require_once 'include/auth.php';
require_once 'include/navbar.php';
require_once 'config/config.php';

// Récupération du rôle
$perm_stmt = mysqli_query($link, "SELECT perm FROM users WHERE id = " . $_SESSION['id_users']);
$perm = mysqli_fetch_assoc($perm_stmt);
$role = $perm['perm'];

// Création ticket
if (isset($_POST['envoyer'])) {
    if (!empty($_POST['titre']) && !empty($_POST['description'])) {
        $sql = "INSERT INTO tickets (titre, description, created, statut, user_id) VALUES (?, ?, NOW(), 'ouvert', ?)";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $_POST['titre'], $_POST['description'], $_SESSION['id_users']);
        mysqli_stmt_execute($stmt);

        $new_id = mysqli_insert_id($link);

        $sql2 = "INSERT INTO messages (ticket_id, user_id, message, created) VALUES (?, ?, ?, NOW())";
        $stmt2 = mysqli_prepare($link, $sql2);
        mysqli_stmt_bind_param($stmt2, "iis", $new_id, $_SESSION['id_users'], $_POST['description']);
        mysqli_stmt_execute($stmt2);

    } else {
        $message = "<span class='message'>* Vous devez remplir tous les champs !!</span>";
    }
}


// Changement de statut (technicien + admin uniquement)
if (isset($_POST['changer_statut']) && in_array($role, ['technicien', 'admin'])) {
    $sql = "UPDATE tickets SET statut = ? WHERE id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "si", $_POST['statut'], $_POST['ticket_id']);
    mysqli_stmt_execute($stmt);

    $msg_sys = "Statut changé -> " . $_POST['statut'];
    $sql2 = "INSERT INTO messages (ticket_id, user_id, message, created) VALUES (?, ?, ?, NOW())";
    $stmt2 = mysqli_prepare($link, $sql2);
    mysqli_stmt_bind_param($stmt2, "iis", $_POST['ticket_id'], $_SESSION['id_users'], $msg_sys);
    mysqli_stmt_execute($stmt2);

    header('Location: test.php?id=' . (int)$_POST['ticket_id'] . '#tickets-view');
    exit;
}

if (isset($_POST['send_message']) && !empty($_POST['message']) && isset($_POST['ticket_id'])) {
    $sql = "INSERT INTO messages (ticket_id, user_id, message, created) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "iis", $_POST['ticket_id'], $_SESSION['id_users'], $_POST['message']);
    mysqli_stmt_execute($stmt);

    header('Location: test.php?id=' . (int)$_POST['ticket_id'] . '#historique');
    exit;
}
 
// Liste des tickets
$stmt = mysqli_query($link, "SELECT id, titre, description, statut, created FROM tickets WHERE user_id = " . (int)$_SESSION['id_users'] . " ORDER BY created DESC");
$tickets = $stmt->fetch_all(MYSQLI_ASSOC);
$selected_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$displaymsg = $selected_id ? 'block' : 'none';
$displaytab = $selected_id ? 'none' : 'block';

// --- Ticket actif ---
$ticket_id = isset($_GET['id']) ? (int)$_GET['id'] : ($tickets[0]['id'] ?? null);
$ticket_actif = null;
$messages = [];

if ($ticket_id) {
    // Infos du ticket
    $stmt = mysqli_prepare($link, "SELECT * FROM tickets WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $ticket_id);
    mysqli_stmt_execute($stmt);
    $ticket_actif = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    // Historique des messages
    $stmt = mysqli_prepare($link, "SELECT * FROM messages WHERE ticket_id = ? ORDER BY id ASC");
    mysqli_stmt_bind_param($stmt, "i", $ticket_id);
    mysqli_stmt_execute($stmt);
    $messages = mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);
}

// --- Couleurs par statut ---
function badge(string $statut): string {
    return match($statut) {
        'ouvert'   => 'background:#dcfce7;color:#166534',
        'en_cours' => 'background:#fef9c3;color:#854d0e',
        'ferme'    => 'background:#f3f4f6;color:#6b7280',
        default    => ''
    };
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpdesk - Lapinski</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/test.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <style>
.tickets-table tbody td a {
  display: block;
  text-decoration: none;
  color: inherit;
}
    </style>
</head>
<body>

<?php if (isset($message)) print($message); ?>

<!-- création ticket -->

<form action="" method="POST" class="ticket-form">
    <div class="field">
        <label>🏷️ Titre</label>
        <input type="text" name="titre" placeholder="Titre du ticket..." autocomplete="off" required>
    </div>
    <div class="field">
        <label>📝 Description</label>
        <input type="text" name="description" placeholder="Décrivez votre problème..." autocomplete="off" required>
    </div>
    <button type="submit" class="btn btn-primary" name="envoyer">🗃️ Créer votre ticket</button>
</form>

<!-- Ticket liste -->

<div class="tickets-container" style="display: <?= $displaytab ?>">
  <table class="tickets-table">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Titre</th>
        <th scope="col">Description</th>
        <th scope="col">Statut</th>
        <th scope="col">Date de création</th>
      </tr>
    </thead>
    <tbody>
  <?php foreach ($tickets as $ticket): ?>
  <tr>
    <td><a href="?id=<?= $ticket['id'] ?>"><?= htmlspecialchars($ticket['id']) ?></a></td>
    <td><a href="?id=<?= $ticket['id'] ?>"><?= htmlspecialchars($ticket['titre']) ?></a></td>
    <td><a href="?id=<?= $ticket['id'] ?>"><?= htmlspecialchars($ticket['description']) ?></a></td>
    <td><a href="?id=<?= $ticket['id'] ?>"><?= htmlspecialchars($ticket['statut']) ?></a></td>
    <td><a href="?id=<?= $ticket['id'] ?>"><?= htmlspecialchars($ticket['created']) ?></a></td>
  </tr>
  <?php endforeach; ?>
</tbody>
  </table>
</div>
<div class="modif-ticket" style="display: <?= $displaymsg ?>">
  <!-- SIDEBAR -->
  <div class="sidebar">
    <div class="sidebar-header" >TICKETS</div>

    <?php foreach ($tickets as $t): ?>
      <a href="?id=<?= $t['id'] ?>" 
         class="sidebar-item <?= $selected_id == $t['id'] ? 'active' : '' ?>">
        <div class="sidebar-item-title"><?= htmlspecialchars($t['titre']) ?></div>
        <span class="sidebar-item-status-nom"><?= htmlspecialchars($t['statut']) ?> </span>
      </a>
    <?php endforeach; ?>
  </div>

<div class="main">

    <?php if ($ticket_actif): ?>

        <!-- En-tête -->
        <div class="main-header">
            <h1><?= htmlspecialchars($ticket_actif['titre']) ?></h1>
            <p><?= htmlspecialchars($ticket_actif['description']) ?></p>
        </div>

        <!-- Historique des messages -->
        <div class="historique" id="historique">
            <?php if (empty($messages)): ?>
                <p class="vide">Aucun message pour ce ticket.</p>
            <?php else: ?>
                <?php foreach ($messages as $msg): ?>
                    <div class="msg">
                        <div class="avatar"><?= htmlspecialchars($msg['user_id']) ?></div>
                        <div class="bulle"><?= htmlspecialchars($msg['message']) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Formulaire d'envoi -->
        <form class="formulaire" method="POST" action="">
            <input type="hidden" name="ticket_id" value="<?= $ticket_id ?>">
            <input type="text" name="message" placeholder="Écrire un message…" autocomplete="off" required>
            <button type="submit" name="send_message">Envoyer</button>
        </form>

    <?php else: ?>
        <p class="vide">Sélectionne un ticket dans la sidebar.</p>
    <?php endif; ?>

</div>


</div>


</body>
</html>