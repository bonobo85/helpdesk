<?php

session_start();

$serveur = "localhost";
    $dbname = "helpdesk_lapinski";
    $login = "root";
    $mdp = "";

    $link = mysqli_connect($serveur, $login, $mdp, $dbname);

    if(!$link){
        die("la connexion a échoué: ".mysqli_connect_error());
    }

    $resultat = mysqli_query($link, "SELECT * FROM users");
        //var_dump($resultat);


$message = "";
$error = false;
      if(isset($_POST['envoyer'])){
      $login = $_POST['nom'];
      $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
      $sql = "select * from users where nom = ? and mdp = ?";
          $stmt = mysqli_prepare($link,$sql);
          mysqli_stmt_bind_param($stmt,"ss",$login,$mdp);
          mysqli_stmt_execute($stmt);
          $resultat2 = mysqli_stmt_get_result($stmt);
          //var_dump($resultat2);
           if(mysqli_num_rows($resultat2) > 0){

              $ligne= mysqli_fetch_assoc($resultat2);

                $_SESSION['id_users'] = $ligne['id'];
                $_SESSION['login'] = $ligne['nom'];


                 header('Location: index.php');
           }
          else{
             $error = true;

           }
      }
      
      
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Helpdesk - Lapinski</title>
  <link rel="stylesheet" href="css/style.css">
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
        
        <ul>
            <li>Pas encore de compte ? <a href="inscription.php">Inscrivez-vous ici</a></li>
        </ul>
      </div>

      <p class="footer-note" style="margin-top:20px;">Labo Informatique · <span>6ème</span></p>
    </div>
  </div>
<script> </script>
  </body>
  </html>
