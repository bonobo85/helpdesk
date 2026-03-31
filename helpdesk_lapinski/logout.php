<?php

session_start();
session_destroy();
header('Location: login.php');
exit();


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpdesk Lapinski Logout</title>
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