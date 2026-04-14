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
        mysqli_stmt_bind_param($stmt, "sss", $_POST['titre'], $_POST['description'], $_SESSION['id_users']);
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

    $msg_sys = "Statut changé → " . $_POST['statut'];
    $sql2 = "INSERT INTO messages (ticket_id, user_id, message, created) VALUES (?, ?, ?, NOW())";
    $stmt2 = mysqli_prepare($link, $sql2);
    mysqli_stmt_bind_param($stmt2, "iis", $_POST['ticket_id'], $_SESSION['id_users'], $msg_sys);
    mysqli_stmt_execute($stmt2);

    header('Location: index.php?id=' . (int)$_POST['ticket_id'] . '#tickets-view');
    exit;
}

// Liste des tickets
$stmt = mysqli_query($link, "SELECT id, titre, statut, created FROM tickets WHERE user_id = " . $_SESSION['id_users'] . " ORDER BY created DESC");
$tickets = $stmt->fetch_all(MYSQLI_ASSOC);
$selected_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$display = $selected_id ? 'block' : 'none';

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

<div class="tickets-container">
  <table class="tickets-table">
    <thead>
      <tr><a href="" ></a>
        <th scope="col">ID</th>
        <th scope="col">Titre</th>
        <th scope="col">Description</th>
        <th scope="col">Statut</th>
        <th scope="col">Date de création</th>
      </tr>
    </thead>
    <tbody>
  <?php foreach ($ticket as $ticket): ?>
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
<div class="modif-ticket" style="display: <?= $display ?>">
  <!-- SIDEBAR -->
  <div class="sidebar">
    <div class="sidebar-header" >TICKETS</div>

    <?php foreach ($tickets as $t): ?>
      <a href="?id=<?= $t['id'] ?>" 
         class="sidebar-item <?= $selected_id == $t['id'] ? 'active' : '' ?>">
        <div class="sidebar-item-title"><?= htmlspecialchars($t['titre']) ?></div>
        <span class="sidebar-item-status-nom"><?= htmlspecialchars($t['statut']) ?> par <?= htmlspecialchars($t['nom']) ?></span>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- messages -->
  <div class="main-content">
    
  </div>

</div>


</body>
</html>