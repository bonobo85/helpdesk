

<?php

// Fichier de configuration local. NE PAS COMMITTER sur GitHub.
// Copiez config/config.example.php vers config/config.php et configurez vos identifiants.

$host = "db.pfrxzlqisqpggeosbrcf.supabase.co";
$db   = "postgres";
$user = "postgres";
$pass = "WizardlyGolf85!";
$port = "5432";






$dsn = "pgsql:host=$host;port=$port;dbname=$db";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "Connecté à Supabase 🚀";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
