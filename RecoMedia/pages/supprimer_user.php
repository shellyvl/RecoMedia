<?php
session_start();
include "../connexion.php";

// ==========================================
// 1. SÉCURITÉ : VÉRIFICATION DU RÔLE ADMIN
// ==========================================
if (!isset($_SESSION['pseudo']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// ==========================================
// 2. SUPPRESSION EN CASCADE
// ==========================================
if (isset($_GET['id'])) {
    $id_a_bannir = (int)$_GET['id'];

    // Étape A : Récupérer le pseudo pour supprimer ses commentaires
    $sql_get_pseudo = "SELECT pseudo FROM utilisateurs WHERE id = $id_a_bannir";
    $res_pseudo = mysqli_query($conn, $sql_get_pseudo);
    
    if (mysqli_num_rows($res_pseudo) > 0) {
        $user = mysqli_fetch_assoc($res_pseudo);
        $pseudo_a_bannir = mysqli_real_escape_string($conn, $user['pseudo']);
        
        // On supprime tous ses commentaires
        mysqli_query($conn, "DELETE FROM commentaires WHERE pseudo = '$pseudo_a_bannir'");
    }

    // Étape B : On supprime tous les films/séries qu'il a ajoutés
    mysqli_query($conn, "DELETE FROM contenus WHERE id_utilisateur = $id_a_bannir");

    // Étape C : On supprime enfin l'utilisateur de la base de données
    mysqli_query($conn, "DELETE FROM utilisateurs WHERE id = $id_a_bannir");
}

// ==========================================
// 3. REDIRECTION
// ==========================================
// On renvoie l'admin sur son panel pour voir que la suppression a bien marché
header("Location: admin.php");
exit();
?>