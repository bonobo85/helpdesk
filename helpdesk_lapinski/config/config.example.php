<?php

// Configuration de la base de données locale
$host = "localhost";
$db   = "votre_base";
$user = "root";
$pass = "";
$port = "5432";

$dsn = "pgsql:host=$host;port=$port;dbname=$db";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "Connecté à la base de données 🚀";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
