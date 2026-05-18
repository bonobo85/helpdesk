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
if (isset($_GET['id'])) {
    $selected_id = (int)$_GET['id'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="css/panel.css">

</head>
<body>
    <h1>Panel</h1>
    <p>Bienvenue sur le panel d'administration, <?= htmlspecialchars($_SESSION['login']) ?> !</p>

<div class="cards-container">
  <div class="card text-center" style="width: 18rem;">
    <div class="card-body">
      <h5 class="card-title">Voir les tickets</h5>
      <p class="card-text">Consulter tous les tickets</p>
      <a href="ticket.php" class="btn btn-primary">Vers les tickets</a>
    </div>
  </div>
  
  <div class="card text-center" style="width: 18rem;">
    <div class="card-body">
      <h5 class="card-title">Gérer les utilisateurs</h5>
      <p class="card-text">Ajouter, modifier ou supprimer des utilisateurs</p>
      <a href="user-list.php" class="btn btn-primary">Gérer les utilisateurs</a>
    </div>
  </div>
  
  <div class="card text-center" style="width: 18rem;">
    <div class="card-body">
      <h5 class="card-title">Créer un utilisateur</h5>
      <p class="card-text">Ajouter un nouvel utilisateur</p>
      <a href="inscription.php" class="btn btn-primary">Créer un utilisateur</a>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>

