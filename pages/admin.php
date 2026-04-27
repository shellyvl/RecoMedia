<?php
session_start();
include "../connexion.php";

// ==========================================
// 1. SÉCURITÉ : VÉRIFICATION DU RÔLE ADMIN
// ==========================================
// Si la personne n'est pas connectée OU n'a pas le rôle 'admin', on la dégage !
if (!isset($_SESSION['pseudo']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// ==========================================
// 2. RÉCUPÉRATION DE TOUTES LES DONNÉES
// ==========================================
// Récupérer tous les utilisateurs
$sql_users = "SELECT * FROM utilisateurs ORDER BY id DESC";
$res_users = mysqli_query($conn, $sql_users);

// Récupérer tous les contenus (avec le pseudo de l'auteur grâce à une jointure JOIN)
$sql_contenus = "SELECT contenus.*, utilisateurs.pseudo AS auteur 
                 FROM contenus 
                 LEFT JOIN utilisateurs ON contenus.id_utilisateur = utilisateurs.id 
                 ORDER BY contenus.id DESC";
$res_contenus = mysqli_query($conn, $sql_contenus);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrateur - RecoMédia</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <header class="main-header">
        <h1><i class="fas fa-lock"></i> Panel Administrateur</h1>
        <p class="header-subtitle">Modération globale du site RecoMédia</p>
    </header>

    <nav>
        <a href="../index.php"><i class="fas fa-home"></i> Accueil</a>
        <a href="dashboard.php"><i class="fas fa-clapperboard"></i> Mon Tableau de Bord</a>
        <a href="logout.php" style="color: #ff4d4d;"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </nav>

    <main class="container">
        
        <section class="glass-section">
            <h2 class="section-title" style="text-align: left; color: #ffcc00;"><i class="fas fa-users"></i> Tous les membres</h2>
            
            <table style="width: 100%; border-collapse: collapse; text-align: left; color: white;">
                <tr style="border-bottom: 2px solid var(--primary);">
                    <th style="padding: 10px;">ID</th>
                    <th style="padding: 10px;">Pseudo</th>
                    <th style="padding: 10px;">Email</th>
                    <th style="padding: 10px;">Rôle</th>
                    <th style="padding: 10px;">Action</th>
                </tr>
                <?php while($user = mysqli_fetch_assoc($res_users)): ?>
                <tr style="border-bottom: 1px solid #444;">
                    <td style="padding: 10px;"><?php echo $user['id']; ?></td>
                    <td style="padding: 10px; font-weight: bold;">@<?php echo htmlspecialchars($user['pseudo']); ?></td>
                    <td style="padding: 10px;"><?php echo htmlspecialchars($user['email']); ?></td>
                    <td style="padding: 10px;">
                        <?php 
                        if(isset($user['role']) && $user['role'] == 'admin') {
                            echo '<span style="color: #ffcc00;">Admin</span>';
                        } else {
                            echo 'Membre';
                        }
                        ?>
                    </td>
                    <td style="padding: 10px;">
                        <?php if($user['pseudo'] !== $_SESSION['pseudo']): // On utilise le pseudo plutôt que l'ID ?>
                            <a href="supprimer_user.php?id=<?php echo $user['id']; ?>" style="color: #ff4d4d; text-decoration: none;" onclick="return confirm('Bannir définitivement ce membre ?');">
                                <i class="fas fa-trash"></i> Bannir
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </section>

        <section class="glass-section" style="margin-top: 30px;">
            <h2 class="section-title" style="text-align: left; color: #ffcc00;"><i class="fas fa-film"></i> Modération des contenus</h2>
            
            <table style="width: 100%; border-collapse: collapse; text-align: left; color: white;">
                <tr style="border-bottom: 2px solid var(--primary);">
                    <th style="padding: 10px;">Titre</th>
                    <th style="padding: 10px;">Type</th>
                    <th style="padding: 10px;">Ajouté par</th>
                    <th style="padding: 10px;">Action</th>
                </tr>
                <?php while($contenu = mysqli_fetch_assoc($res_contenus)): ?>
                <tr style="border-bottom: 1px solid #444;">
                    <td style="padding: 10px;"><strong><?php echo htmlspecialchars($contenu['titre']); ?></strong></td>
                    <td style="padding: 10px;"><?php echo htmlspecialchars($contenu['type']); ?></td>
                    <td style="padding: 10px; color: #bbb;">@<?php echo htmlspecialchars($contenu['auteur']); ?></td>
                    <td style="padding: 10px;">
                        <a href="supprimer.php?id=<?php echo $contenu['id']; ?>" style="color: #ff4d4d; text-decoration: none;" onclick="return confirm('Supprimer ce contenu du site ?');">
                            <i class="fas fa-trash"></i> Supprimer
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </section>

    </main>

    <footer style="text-align: center; padding: 20px; color: #888;">
        &copy; 2026 - RecoMédia - Panel Administrateur
    </footer>
</body>
</html>