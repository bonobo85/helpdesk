<?php

require_once 'include/navbar.php';

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




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tickets</title>
    <script src="css/index.js"></script>
</head>
<body>
    

</body>
</html>