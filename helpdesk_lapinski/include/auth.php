<?php
session_start();

if (!isset($_SESSION['id_users'])) {
    header('Location: login.php');
    exit;
}
?>