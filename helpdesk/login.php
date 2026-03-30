<?php

session_start();

// connection à la base de données supabase


session_start();

$host = 'https://aslkryygmrdnqjezofoa.supabase.co';
$port = '5432';
$dbname = 'postgres';
$user = 'postgres';
$password = 'ton_mot_de_passe';

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

    $stmt = $pdo->query("SELECT * FROM utilisateurs");
    $resultat = $stmt->fetchAll();

    var_dump($resultat);
} catch (PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}
          
      
        
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Helpdesk Lapinski – Connexion</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>




</body>
</html>
