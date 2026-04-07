<?php
session_start();

require_once 'include/navbar.php';
require_once 'config/config.php';

if(isset($_POST['btn-submit'])){
      
          if(!empty($_POST['titre']) && !empty($_POST['description'])){
        if($_POST['titre'] == $_POST['description']){
          $sql = "insert into tickets (titre,description,created) values (?,?,NOW())";
          $stmt = mysqli_prepare($link, $sql);
          mysqli_stmt_bind_param($stmt,"ss",$_POST['titre'],$_POST['description']);
          if(mysqli_stmt_execute($stmt)){

            header('Location: index.php');
          }
        }
        
      }
      else{
        $message = "<span class='message'>* Vous devez remplir tous les champs !!</span>";
      }
      }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpdesk - Lapinski</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

<form  action="" method="POST">
      
        <div class="field">
          <label>👤 Titre</label>
          <input type="text" name="titre" placeholder="Ton pseudo..." autocomplete="off" required>
        </div>
        <div class="field">
          <label>Description</label>
          <input type="password" name="description" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary" name="envoyer">
              🗃️ Créer votre ticket
        </button>
      </form>
   
</body>
</html>