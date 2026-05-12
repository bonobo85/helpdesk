<?php

session_start();

require_once 'include/navbar.php';
require_once 'config/config.php';

$message = "";

if(isset($_POST['btn-submit'])){

    if(!empty($_POST['mdp']) && !empty($_POST['conf_mdp'])){
        if($_POST['mdp'] == $_POST['conf_mdp']){

            $hash = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
            $role = $_POST['permissions']; // ✅ récupération du rôle

            $sql = "INSERT INTO users (nom, email, mot_de_passe, role, cree_le) VALUES (?,?,?,?,now())";
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $_POST['nom'], $_POST['mail'], $hash, $role); // ✅ 4 paramètres

            if(mysqli_stmt_execute($stmt)){
                header('Location: index.php');
                exit();
            } else {
                $message = "<span class='message'>* Erreur lors de la création !</span>";
            }

        } else {
            $message = "<span class='message'>* Les mots de passe ne correspondent pas !!</span>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Helpdesk - Lapinski</title>
  <link rel="stylesheet" href="css/connexion.css">
</head>
<body>

  <div class="page">
    <div class="card">
      <h1 class="titre-principal">Helpdesk - Lapinski</h1>
      <p class="sous-titre">Créer un utilisateur</p>

      <?= $message ?> <!-- ✅ affichage du message d'erreur -->

      <form action="" method="POST">

        <div class="field">
          <label>👤 Identifiant</label>
          <input type="text" name="nom" placeholder="Ton pseudo..." autocomplete="off" required>
        </div>
        <div class="field">
          <label>📧 Email</label>
          <input type="email" name="mail" placeholder="Ton email..." autocomplete="off" required>
        </div>
        <div class="field">
          <label>🔒 Mot de passe</label>
          <input type="password" name="mdp" placeholder="••••••••" required>
        </div>
        <div class="field">
          <label>🔒 Confirmation du mot de passe</label>
          <input type="password" name="conf_mdp" placeholder="••••••••" required>
        </div>
        <div class="field">
          <label>🛡️ Permissions</label>
          <select name="permissions" id="permissions">
            <option value="user">Utilisateur</option>
            <option value="technicien">Technicien</option>
            <option value="admin">Administrateur</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary" name="btn-submit">
          🗃️ Créer l'utilisateur
        </button>

      </form>

      <p class="footer-note" style="margin-top:20px;">Labo Informatique · <span>6ème</span></p>
    </div>
  </div>

</body>
</html>