<?php

session_start();

// connection à la base de données supabase


session_start();

$host = 'https://aslkryygmrdnqjezofoa.supabase.co';
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
<!doctype html>
<html lang="fr" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aguerta 18 — Connexion</title>
  <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script> // Librairie Supabase JS

</head>
<body>
  <div id="loginScreen" class="h-full w-full flex items-center justify-center" style="background:radial-gradient(ellipse at 30% 20%,#1a1510 0%,#000 70%)">
    <div class="text-center fade-up p-8">
      <img src="https://th.bing.com/th/id/R.7d0b327653bbcbc1b22e4cc2e8c8e054?rik=ajBkg%2fsALwHD2g&riu=http%3a%2f%2ferpconnectconsulting.com%2fcdn%2fshop%2ffiles%2fHELP-DESK.png%3fv%3d1698247465&ehk=vLL%2btolx0HIIqjbLV177TCh0DAqP0coQ2TATtTClkbs%3d&risl=&pid=ImgRaw&r=0" alt="Aguerta 18" class="w-28 h-28 mx-auto mb-6 object-contain">
      <h1 class="titre">Help Desk -</h1>
      <p class="sous-titre">Base de données privée</p>
     
      
    </div>
  </div>

<script>

    <!-- Connnexion à la Base Supabase -->
const SUPABASE_URL = 'https://lcwtiaohaedeuelzbvfk.supabase.co';
const SUPABASE_ANON_KEY = 'sb_publishable_HDoqR8hhBOuwNZ02urobqQ_v08L1EEr'; // ← Remplace par ta clé anon (Settings > API)
const sb = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);
</script>

</body>
</html>



</body>
</html>
