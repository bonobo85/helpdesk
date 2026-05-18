<?php

session_start(); // pour démarrer une session et stocker des données utilisateur à travers les pages

//  session_start();session_unset();session_destroy(); (une ligne chacun ) pour supprimer les données de session et déconnecter l'utilisateur

require_once 'navbar.php'; // pour inclure une page directement (ex : navbar , header, footer etc)
 // connexion à la bdd 
 $serveur = '192.168.1.253';
 $login = '6qib';
 $mdp = 'Irc2026';
 $bdname = 'irigaray'

 // création de la connexion
 $link = mysqli_connect($serveur,$login,$mdp,$bdname);

 // vérification de la connexion

 if(!$link){
    die("La connexion a échoué : ".mysqli_connect_error());
 }
 
 // login avec mdp hashé
 if(isset($_POST['envoyer'])){
    $login = $_POST['nom'];
    $mdp   = $_POST['mdp'];

    $sql  = "SELECT * FROM users WHERE nom = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);
    $resultat2 = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($resultat2) > 0){
        $ligne = mysqli_fetch_assoc($resultat2);

        var_dump($ligne['mot_de_passe']);

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
var_dump($ligne['mot_de_passe']); // pour voir le mdp hashé 

// login sans mdp hashé
if(isset($_POST['envoyer'])){
    $login = $_POST['nom'];
    $mdp   = $_POST['mdp'];

    $sql  = "SELECT * FROM users WHERE nom = ? AND mot_de_passe = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $login, $mdp);
    mysqli_stmt_execute($stmt);
    $resultat2 = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($resultat2) > 0){
        $ligne = mysqli_fetch_assoc($resultat2);
        $_SESSION['id_users'] = $ligne['id'];
        $_SESSION['login']    = $ligne['nom'];
        header('Location: index.php');
        exit();
    } else {
        $error = true;
    }
}


 
 // inscription avec mdp hashé
if(isset($_POST['btn-submit'])){

    if(!empty($_POST['mdp']) && !empty($_POST['conf_mdp'])){
        if($_POST['mdp'] == $_POST['conf_mdp']){

            $hash = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
            $role = $_POST['permissions']; 

            $sql = "INSERT INTO users (nom, email, mot_de_passe, , cree_le) VALUES (?,?,?,now())";
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $_POST['nom'], $_POST['mail'], $hash, $role); 

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

// inscription sans mdp hashé
if(isset($_POST['btn-submit'])){

    if(!empty($_POST['mdp']) && !empty($_POST['conf_mdp'])){
        if($_POST['mdp'] == $_POST['conf_mdp']){

            $sql = "INSERT INTO users (nom, email, mot_de_passe,, cree_le) VALUES (?,?,?,now())";
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $_POST['nom'], $_POST['mail'], $_POST['mdp'], $_POST['permissions']); 

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

 // select 
  $select_normal = mysqli_query($link,"SELECT * From films ");

       // select particulier  
       $select_particulier = mysqli_prepare($link,"SELECT * from films where titre= ? ");
        mysqli_stmt_bind_param($stmt,"i",$titre); // $titre devra être déclare par la suite 
        mysqli_stmt_execute($stmt);
       
  
 // insert
    $insert_normal = mysqli_query($link,"INSERT INTO films (titre,annee) VALUES ('nouveau film',2024)");
    
            // insert particulier
            $insert_particulier= mysqli_prepare($link,"INSERT INTO films (titre,annee) VALUES (?,?)");
            mysqli_stmt_bind_param($stmt,"si",$titre,$annee); // $titre et $annee devront être déclarés par la suite 
            mysqli_stmt_execute($stmt);

 // delete
 $delete_normal = mysqli_query($link,"DELETE * FROM films");

       //delete particulier
        $delete_particulier= mysqli_prepare($link,"delete from films where id= ? ");
        mysqli_stmt_bind_param($stmt,"i",$id); // $id devra être déclaré par la suite 
        mysqli_stmt_execute($stmt);
    
 // update
    $update_normal = mysqli_query($link,"UPDATE films set titre='nouveau titre' where id=1");
    
        // update particulier
            $update_particulier= mysqli_prepare($link,"UPDATE films set titre= ? where id= ? ");
            mysqli_stmt_bind_param($stmt,"si",$titre,$id); // $titre et $id devront être déclarés par la suite 
            mysqli_stmt_execute($stmt);

// isset 

   //isset permet de vérifier si une variable est définie et n'est pas nulle. Par exemple, on peut utiliser isset pour vérifier si un formulaire a été soumis avant de traiter les données

   // exemple 
if(isset($_POST['submit'])){
    // si le formulaire submit est défini, on peut traiter les données du formulaire ici
    $titre = $_POST['titre'];
    $annee = $_POST['annee'];
    $id = $_POST['id'];
    // ensuite, on peut utiliser ces variables pour faire une requête d'insertion dans la base de données
    $insert_particulier= mysqli_prepare($link,"INSERT INTO films (titre,annee) VALUES (?,?)");
    mysqli_stmt_bind_param($stmt,"si",$titre,$annee);
    mysqli_stmt_execute($stmt);
    // ou update les données
    $update_particulier= mysqli_prepare($link,"UPDATE films set titre= ? where id= ? ");
    mysqli_stmt_bind_param($stmt,"si",$titre,$id);
    mysqli_stmt_execute($stmt);
    // ou delete les données
    $delete_particulier= mysqli_prepare($link,"delete from films where id= ? ");
    mysqli_stmt_bind_param($stmt,"i",$id);
    mysqli_stmt_execute($stmt);
}

 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sythèse pour le cours de Mr.Irigaray</title>
</head>
<body>

 <!-- Navbar  -->
    <h2>Navbar</h2>
    <nav class="navbar">
        <ul class="nav-list">
            <li><a href=".login-page">Login</a></li>
            <li><a href=".inscription-page">Inscription</a></li>
            <li><a href=".inputs-page">Inputs</a></li>
            <li><a href="#">Déconnexion</a></li>
        </ul>

 <!-- Login  -->

<h2>Login</h2>
<div class="login-page">
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

<!-- Inscription -->
<h2>Inscription</h2>
<div class="inscription-page">
    <div class="card">
      <h1 class="titre-principal">Helpdesk - Lapinski</h1>
      <p class="sous-titre">Créer un utilisateur</p>

      <?= $message ?> 

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

<!-- Différent types d'input -->


<h2>Différent type d'input </h2>
<div style='width:400px;margin:30px auto' class="inputs-page">
<form action="" method="post">
<table>

<tr><td>Select parmis 3 options | input </td><td>
<select name="image">
<option value="soleil">Soleil</option>
<option value="mars">Mars</option>
<option value="jupiter">Jupiter</option>
</select>
</td></tr>

<tr><td>Entrez la taille de l'image</td><td><input type="number" name="taille" min="0"></td></tr>
<tr><td>Input pour changer la couleur avec la palette | color </td><td>
<input type="color" name="bordcol" value="#00ffff" /></td></tr>

<tr><td>Input à sélectionner parmis circulaire | radio</td><td>
<input type="radio" name="qty" value="1" />Une<br/>
<input type="radio" name="qty" value="2" />Deux<br/>
<input type="radio" name="qty" value="3" />Trois<br/>
<input type="radio" name="qty" value="4" />Quatre<br/>

</td></tr>

<tr><td>Input à cocher | checkbox</td><td>
<input type="checkbox" value="top" name="bordures[]">Haut<br/>
<input type="checkbox" value="bottom" name="bordures[]">Bas<br/>
<input type="checkbox" value="right" name="bordures[]">Droite<br/>
<input type="checkbox" value="left" name="bordures[]">Gauche<br/>

</td></tr>

<tr>Input de validation | submit<td>
</td><td>
<input type="submit" value="Valider" />
</td></tr>
</table>
</form>
</div>
</body>
</html>