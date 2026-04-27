<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "recomedia";
$port = 3307; // Ton nouveau port MySQL

// On ajoute le $port à la fin de la ligne de connexion
$conn = mysqli_connect($host, $user, $password, $dbname, $port);
mysqli_set_charset($conn, "utf8mb4");
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}
//echo "Connexion réussie à la base de données !";
?>