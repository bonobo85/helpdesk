<?php
session_start();
session_unset();
session_destroy();






?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Déconnexion</title>
  <meta http-equiv="refresh" content="2;url=login.php">
  <link rel="stylesheet" href="../css/style.css">
  <style>
    body{
      background: #833AB4;
      background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(0,0,255,1) 60%, rgba(0,212,255,1) 100%);
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      margin: auto auto;
    }
    
  </style>
</head>
<body>
  <div class="page">
    <div class="card" style="text-align:center;">
      <div style="font-size:3rem; margin-bottom:12px;">👋</div>
      <h2 style="font-family:'Fredoka One',cursive; font-size:1.6rem; margin-bottom:8px;">À bientôt !</h2>
      <p style="color:rgba(255,255,255,.4); font-size:13px; font-weight:700;">Redirection vers le login...</p>
    </div>
  </div>
</body>
</html>
