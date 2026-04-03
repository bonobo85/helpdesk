<?php
session_start();

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
    <title>Helpdesk - Lapinski</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

        <!-- Submit Ticket -->


        <!-- View Tickets -->

        <!-- Content for viewing tickets -->


        <!-- Admin Panel -->

        <!-- Content for admin panel -->


    <!-- Contenu centré -->
    <div class="main-content">
        <!-- ici tu peux mettre ta card ou autre -->
    </div>

</body>
</html>