<?php
session_start(); // On récupère la session en cours
session_unset(); // On efface toutes les variables de session (pseudo, etc.)
session_destroy(); // On détruit techniquement la session sur le serveur

// Redirection vers la page de connexion avec un petit message de confirmation
header("Location: login.php?deconnecte=1");
exit();
?>