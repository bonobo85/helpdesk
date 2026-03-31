<?php

session_start();

// Connexion à la base de données Supabase 
$host = 'aslkryygmrdnqjezofoa.supabase.co';
$port = '5432';
$dbname = 'postgres';
$user = 'postgres';
$password = 'WizardlyGolf85!';

try {
  $pdo = new PDO(
    "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require",
    $user,
    $password,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
  );
} catch (PDOException $e) {
  die("Connexion échouée : " . $e->getMessage());
}

$erreur = false;
if (isset($_POST['envoyer'])) {
  $login = $_POST['login'] ?? '';
  $mdp = $_POST['mdp'] ?? '';
  if ($login && $mdp) {
    // On suppose que le champ "email" est utilisé comme identifiant
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute(['email' => $login]);
    $user = $stmt->fetch();
    if ($user && password_verify($mdp, $user['mot_de_passe'])) {
      // Connexion réussie
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_nom'] = $user['nom'];
      $_SESSION['user_role'] = $user['role'];
      header('Location: index.php');
      exit;
    } else {
      $erreur = true;
    }
  } else {
    $erreur = true;
  }
}
          
      
        
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Helpdesk - Lapinski</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>

  <div class="page">
    <div class="card">
      <h1 class="titre-principal">Helpdesk - Lapinski</h1>
      <p class="sous-titre">Connectez-vous pour accéder à votre espace</p>

      <!-- Message d'erreur affiché par PHP si le login échoue -->

      <?php if ($erreur): ?>
      <div class="msg-erreur" id="erreur" >
        ❌ Identifiant ou mot de passe incorrect !
      </div>
      <?php endif; ?>

      <form  action="" method="POST">
      
        <div class="field">
          <label>👤 Identifiant</label>
          <input type="text" name="login" placeholder="Ton pseudo..." autocomplete="off" required>
        </div>
        <div class="field">
          <label>🔒 Mot de passe</label>
          <input type="password" name="mdp" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary" name="envoyer">
           🗃️ Accéder à mon espace
        </button>
      </form>

      <p class="footer-note" style="margin-top:20px;">Labo Informatique · <span>6ème</span></p>
    </div>
  </div>
<script> </script>
  </body>
  </html>
