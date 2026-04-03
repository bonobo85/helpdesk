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


    $message="";

    if(isset($_POST['btn-submit'])){
      
          if(!empty($_POST['mdp']) && !empty($_POST['conf_mdp'])){
        if($_POST['mdp'] == $_POST['conf_mdp']){
          $sql = "insert into users (nom,email,mdp,perm,created) values (?,?,?,user,now())";
          $stmt = mysqli_prepare($link, $sql);
          mysqli_stmt_bind_param($stmt,"sss",$_POST['nom'],$_POST['mail'],$_POST['mdp']);
          if(mysqli_stmt_execute($stmt)){

            header('Location: ../index.php');
          }
        }
        else{
          $message = "<span class='message'>* Les mots de passe ne correspondent pas !!</span>";
        }
      }
      else{
        $message = "<span class='message'>* Vous devez remplir tous les champs !!</span>";
      }
      }
    
    

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Helpdesk - Lapinski</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

  <div class="page">
    <div class="card">
      <h1 class="titre-principal">Helpdesk - Lapinski</h1>
      <p class="sous-titre">Inscrivez-vous pour accéder à votre espace</p>
      <form  action="" method="POST">
      
        <div class="field">
          <label>👤 Identifiant</label>
          <input type="text" name="nom" placeholder="Ton pseudo..." autocomplete="off" required>
        </div>
        <div class="field">
          <label> 📧 Email</label>
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
        <button type="submit" class="btn btn-primary" name="btn-submit">
              🗃️ Accéder à mon espace
        </button>
      </form>
      <div class="login-link" style="margin-top:20px;">
        
        <ul>
            <li>Déjà un compte ? <a href="login.php">Connectez-vous ici</a></li>
        </ul>
    </div>


      <p class="footer-note" style="margin-top:20px;">Labo Informatique · <span>6ème</span></p>
    </div>
  </div>
<script> </script>
  </body>
  </html>
