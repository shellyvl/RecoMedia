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
// 2. RÉCUPÉRATION DES DONNÉES
// ==========================================
$sql_users = "SELECT * FROM utilisateurs ORDER BY id DESC";
$res_users = mysqli_query($conn, $sql_users);

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
        <a href="logout.php" class="action-delete"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </nav>

    <main class="container">
        
        <section class="glass-section">
            <h2 class="section-title"><i class="fas fa-users"></i> Tous les membres</h2>
            
            <table class="admin-table">
                <tr>
                    <th>ID</th>
                    <th>Pseudo</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Action</th>
                </tr>
                <?php while($user = mysqli_fetch_assoc($res_users)): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><strong>@<?php echo htmlspecialchars($user['pseudo']); ?></strong></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <?php 
                        if(isset($user['role']) && $user['role'] == 'admin') {
                            echo '<span class="badge-admin">Admin</span>';
                        } else {
                            echo 'Membre';
                        }
                        ?>
                    </td>
                    <td>
                        <?php if($user['pseudo'] !== $_SESSION['pseudo']): // L'admin ne se bannit pas lui-même ?>
                            <a href="supprimer_user.php?id=<?php echo $user['id']; ?>" class="action-delete" onclick="return confirm('Bannir définitivement ce membre ?');">
                                <i class="fas fa-trash"></i> Bannir
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </section>

        <section class="glass-section">
            <h2 class="section-title"><i class="fas fa-film"></i> Modération des contenus</h2>
            
            <table class="admin-table">
                <tr>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Ajouté par</th>
                    <th>Action</th>
                </tr>
                <?php while($contenu = mysqli_fetch_assoc($res_contenus)): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($contenu['titre']); ?></strong></td>
                    <td><?php echo htmlspecialchars($contenu['type']); ?></td>
                    <td class="text-muted">@<?php echo htmlspecialchars($contenu['auteur']); ?></td>
                    <td>
                        <a href="supprimer.php?id=<?php echo $contenu['id']; ?>" class="action-delete" onclick="return confirm('Supprimer ce contenu du site ?');">
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