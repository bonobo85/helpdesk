<?php

require_once 'include/navbar.php';
require_once 'config/config.php';

if(isset($_POST['envoyer'])){
      
          if(!empty($_POST['titre']) && !empty($_POST['description'])){
        
          $sql = "insert into tickets (titre,description,created,statut,user_id) values (?,?,NOW(),'ouvert',?)";  
          $stmt = mysqli_prepare($link, $sql);
           mysqli_stmt_bind_param($stmt,"sss",$_POST['titre'],$_POST['description'],$_SESSION['id_users']);
           mysqli_stmt_execute($stmt);
        
      }
      else{
        $message = "<span class='message'>* Vous devez remplir tous les champs !!</span>";
      }
}
$stmt = mysqli_query($link, "SELECT id, titre, description, statut, user_id, created FROM tickets where user_id = ".$_SESSION['id_users']);
$ticket = $stmt->fetch_all(MYSQLI_ASSOC);




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpdesk - Lapinski</title>
    <link rel="stylesheet" href="css/creation.css">
</head>
<body>

<?php if(isset($message)) echo $message; ?>
<form  action="" method="POST" class="ticket-form">
      
        <div class="field">
          <label>🏷️ Titre</label>
          <input type="text" name="titre" placeholder="Titre du ticket..." autocomplete="off" required>
        </div>
        <div class="field">
          <label>📝 Description</label>
          <input type="text" name="description" placeholder="Décrivez votre problème..." autocomplete="off" required>
        </div>
        <button type="submit" class="btn btn-primary" name="envoyer">
              🗃️ Créer votre ticket
        </button>
</form>

<div class="tickets-container">
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
      <?php foreach ($ticket as $ticket): ?>
      <tr>
        <td><?= htmlspecialchars($ticket['id']) ?></td>
        <td><?= htmlspecialchars($ticket['titre']) ?></td>
        <td><?= htmlspecialchars($ticket['description']) ?></td>
        <td><?= htmlspecialchars($ticket['statut']) ?></td>
        <td><?= htmlspecialchars($ticket['created']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
   
</body>
</html>