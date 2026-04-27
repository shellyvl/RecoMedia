<?php
session_start();
// 1. On se connecte à la base
include "../connexion.php"; 

// 2. On récupère l'ID du film cliqué dans l'URL (le fameux ?id=...)
$id = $_GET['id']; 

// 3. On prépare l'ordre de destruction !
$sql = "DELETE FROM contenus WHERE id=$id"; 

// 4. On exécute l'ordre
mysqli_query($conn, $sql); 

// 5. On redirige vers le tableau de bord ni vu ni connu
header("Location: dashboard.php"); 
exit();
?>