<?php

$serveur = "192.168.1.253";
    $dbname = "helpdesk_irigaray";
    $login = "6qib";
    $mdp = "Irc2026";

    $link = mysqli_connect($serveur, $login, $mdp, $dbname);

    if(!$link){
        die("la connexion a échoué: ".mysqli_connect_error());
    }

    $resultat = mysqli_query($link, "SELECT * FROM users");
        //var_dump($resultat);


?>