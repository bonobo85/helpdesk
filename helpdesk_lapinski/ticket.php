<?php

require_once 'include/auth.php';
require_once 'include/navbar.php';
require_once 'config/config.php';

// Pagination
$par_page = 10;
$page_actuelle = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page_actuelle - 1) * $par_page;

// Total
$total = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) as total FROM tickets"))['total'];
$total_pages = ceil($total / $par_page);

// Tickets de la page
$stmt = mysqli_prepare($link, "SELECT id, titre, description, statut, user_id, cree_le FROM tickets ORDER BY cree_le DESC LIMIT ? OFFSET ?");
mysqli_stmt_bind_param($stmt, "ii", $par_page, $offset);
mysqli_stmt_execute($stmt);
$tickets = mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);

$selected_id = "";
if(isset($select_id))

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tickets</title>
    <link rel="stylesheet" href="css/ticket.css">
</head>
<body>

<div class="tickets-container">
  <table class="tickets-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Titre</th>
        <th>Description</th>
        <th>Statut</th>
        <th>User ID</th>
        <th>Date de création</th>
        <th> Voir le ticket</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($tickets as $ticket): ?>
      <tr >
        <td><?= htmlspecialchars($ticket['id']) ?></td>
        <td><?= htmlspecialchars($ticket['titre']) ?></td>
        <td><?= htmlspecialchars($ticket['description']) ?></td>
        <td><?= htmlspecialchars($ticket['statut']) ?></td>
        <td><?= htmlspecialchars($ticket['user_id']) ?></td>
        <td><?= htmlspecialchars($ticket['cree_le']) ?></td>
        <td><a class="btn btn-primary" href="historique.php?id=<?= htmlspecialchars($ticket['id']) ?>" role="button"><input class="btn btn-primary" type="submit" value="voir"></a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  

</div>

</body>
</html>