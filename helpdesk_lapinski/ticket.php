<?php

require_once 'include/navbar.php';
require_once 'config/config.php';

$stmt = mysqli_query($link, "SELECT id, titre, description, statut, user_id, created FROM tickets");
$ticket = $stmt->fetch_all(MYSQLI_ASSOC);

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
        <th scope="col">ID</th>
        <th scope="col">Titre</th>
        <th scope="col">Description</th>
        <th scope="col">Statut</th>
        <th scope="col">User ID</th>
        <th scope="col">Date de création</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($ticket as $ticket): ?>
      <tr>
        <td><?= htmlspecialchars($ticket['id']) ?></td>
        <td><?= htmlspecialchars($ticket['titre']) ?></td>
        <td><?= htmlspecialchars($ticket['description']) ?></td>
        <td><?= htmlspecialchars($ticket['statut']) ?></td>
        <td><?= htmlspecialchars($ticket['user_id']) ?></td>
        <td><?= htmlspecialchars($ticket['created']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>