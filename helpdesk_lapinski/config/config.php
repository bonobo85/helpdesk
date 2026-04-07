<?php

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