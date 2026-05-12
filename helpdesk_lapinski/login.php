<?php

session_start();

require_once 'config/config.php';

$message = "";
$error = false;

if(isset($_POST['envoyer'])){
    $login = $_POST['nom'];
    $mdp   = $_POST['mdp'];

    // On cherche uniquement par nom (pas le mdp en clair)
    $sql  = "SELECT * FROM users WHERE nom = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);
    $resultat2 = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($resultat2) > 0){
        $ligne = mysqli_fetch_assoc($resultat2);

        // var_dump du hash stocké en base
        var_dump($ligne['mot_de_passe']);

        // Vérification du mot de passe
        if(password_verify($mdp, $ligne['mot_de_passe'])){
            $_SESSION['id_users'] = $ligne['id'];
            $_SESSION['login']    = $ligne['nom'];
            header('Location: index.php');
            exit();
        } else {
            $error = true;
        }
    } else {
        $error = true;
    }
}
var_dump($ligne['mot_de_passe']);
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
      <p class="sous-titre">Connectez-vous pour accéder à votre espace</p>

      <?php if ($error): ?>
      <div class="msg-erreur" id="erreur" >
        ❌ Identifiant ou mot de passe incorrect !
      </div>
      <?php endif; ?>

      <form  action="" method="POST">
      
        <div class="field">
          <label>👤 Identifiant</label>
          <input type="text" name="nom" placeholder="Ton pseudo..." autocomplete="off" required>
        </div>
        <div class="field">
          <label>🔒 Mot de passe</label>
          <input type="password" name="mdp" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary" name="envoyer">
              🗃️ Accéder à mon espace
        </button>
      </form>
            <div class="login-link" style="margin-top:20px;">
        
      
      </div>

      <p class="footer-note" style="margin-top:20px;">Labo Informatique · <span>6ème</span></p>
    </div>
  </div>
<script> </script>
  </body>
  </html>
