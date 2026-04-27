<?php
// ==========================================
// 1. INITIALISATION ET RÉCUPÉRATION
// ==========================================
session_start();
include "../connexion.php";

if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$id_auteur = (int) $_GET['id'];

// Récupération des infos du membre
$sql_user = "SELECT pseudo, bio FROM utilisateurs WHERE id = $id_auteur";
$res_user = mysqli_query($conn, $sql_user);

if (mysqli_num_rows($res_user) == 0) {
    die("Utilisateur introuvable.");
}
$user_data = mysqli_fetch_assoc($res_user);
$pseudo_auteur = mysqli_real_escape_string($conn, $user_data['pseudo']);
$bio = !empty($user_data['bio']) ? $user_data['bio'] : "Membre passionné(e) de cinéma.";

// ==========================================
// 2. STATISTIQUES
// ==========================================

// Compte des recommandations
$sql_count_recos = "SELECT COUNT(*) as total FROM contenus WHERE id_utilisateur = $id_auteur";
$res_count_recos = mysqli_query($conn, $sql_count_recos);
$total_recos = mysqli_fetch_assoc($res_count_recos)['total'];

// Compte des avis laissés
$sql_count_avis = "SELECT COUNT(*) as total FROM commentaires WHERE pseudo = '$pseudo_auteur'";
$res_count_avis = mysqli_query($conn, $sql_count_avis);
$total_avis = mysqli_fetch_assoc($res_count_avis)['total'];

// ==========================================
// 3. RÉCUPÉRATION DES LISTES (CONTENUS & AVIS)
// ==========================================

// Liste des contenus ajoutés
$query_contenus = "SELECT * FROM contenus WHERE id_utilisateur = $id_auteur ORDER BY id DESC";
$result_contenus = mysqli_query($conn, $query_contenus);

// Liste des 5 derniers avis
$sql_derniers_avis = "SELECT commentaires.*, contenus.titre 
                      FROM commentaires 
                      JOIN contenus ON commentaires.id_contenu = contenus.id 
                      WHERE commentaires.pseudo = '$pseudo_auteur' 
                      ORDER BY commentaires.id DESC LIMIT 5";
$result_derniers_avis = mysqli_query($conn, $sql_derniers_avis);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Profil de <?php echo htmlspecialchars($user_data['pseudo']); ?> - RecoMédia</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <header class="main-header">
        <h1> Profil de <?php echo htmlspecialchars($user_data['pseudo']); ?></h1>
    </header>

    <nav>
        <a href="../index.php"><i class="fas fa-home"></i> Accueil</a>
        <?php if (isset($_SESSION['pseudo'])): ?>
            <a href="dashboard.php"><i class="fas fa-clapperboard"></i> Tableau de Bord</a>
            <a href="profil.php"><i class="fas fa-user-circle"></i> Mon Profil</a>
            <a href="logout.php" style="color: #ff4d4d;"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        <?php else: ?>
            <a href="login.php"><i class="fas fa-sign-in-alt"></i> Connexion</a>
        <?php endif; ?>
    </nav>

    <main class="container">
        <section class="glass-section profile-header" style="display: flex; align-items: center; gap: 30px;">
            <div class="profile-avatar"><?php echo strtoupper($user_data['pseudo'][0]); ?></div>

            <div class="profile-info"
                style="flex: 1; display: flex; justify-content: space-between; align-items: center;">

                <div style="flex: 1; padding-right: 20px;">
                    <h2 style="margin:0; color: white;"><?php echo htmlspecialchars($user_data['pseudo']); ?></h2>
                    <p style="color: #bbb; font-style: italic; margin: 5px 0 0 0;">
                        "<?php echo htmlspecialchars($bio); ?>"</p>
                </div>

                <div style="display: flex; gap: 30px; text-align: center;">
                    <div>
                        <span
                            style="font-size: 1.8rem; font-weight: bold; color: var(--primary);"><?php echo $total_recos; ?></span>
                        <span
                            style="display: block; font-size: 0.85rem; color: #888; text-transform: uppercase;">Recos</span>
                    </div>
                    <div>
                        <span
                            style="font-size: 1.8rem; font-weight: bold; color: var(--primary);"><?php echo $total_avis; ?></span>
                        <span style="display: block; font-size: 0.85rem; color: #888; text-transform: uppercase;">Avis
                            postés</span>
                    </div>
                </div>

            </div>
        </section>

        <section class="glass-section">
            <h2 class="section-title" style="text-align: left;">Ses recommandations</h2>

            <?php if (mysqli_num_rows($result_contenus) > 0): ?>
                <div class="recommandations-container">
                    <?php while ($row = mysqli_fetch_assoc($result_contenus)): ?>
                        <a href="detail.php?id=<?php echo $row['id']; ?>" style="text-decoration: none; display: block;">
                            <div class="reco-card">
                                <img src="<?php echo htmlspecialchars($row['image']); ?>"
                                    alt="<?php echo htmlspecialchars($row['titre']); ?>">
                                <h3><?php echo htmlspecialchars($row['titre']); ?></h3>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p style="color: #888;">Cet utilisateur n'a pas encore ajouté de contenu.</p>
            <?php endif; ?>
        </section>

        <section class="glass-section" style="margin-top: 30px;">
            <h2 class="section-title" style="text-align: left;">Ses derniers avis</h2>

            <?php if (mysqli_num_rows($result_derniers_avis) > 0): ?>
                <?php while ($avis = mysqli_fetch_assoc($result_derniers_avis)): ?>
                    <div class="comment-card"
                        style="display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: 15px;">
                        <div style="flex: 1;">
                            <div style="color: #ffcc00; font-size: 1em; margin-bottom: 5px;">
                                <?php
                                for ($i = 0; $i < $avis['note']; $i++) {
                                    echo "★";
                                }
                                for ($i = $avis['note']; $i < 5; $i++) {
                                    echo "☆";
                                }
                                ?>
                            </div>
                            <p style="margin: 0;">
                                <em>"<?php echo nl2br(htmlspecialchars($avis['texte'])); ?>"</em> <br>
                                <span style="font-size: 0.8rem; color: #888;">
                                    Avis laissé sur : <strong><?php echo htmlspecialchars($avis['titre']); ?></strong>
                                </span>
                            </p>
                        </div>
                        <div>
                            <a href="detail.php?id=<?php echo $avis['id_contenu']; ?>"
                                style="display: inline-block; background: var(--primary); color: white; padding: 8px 15px; text-decoration: none; border-radius: 30px; font-size: 0.8rem; font-weight: bold; white-space: nowrap; transition: 0.3s; box-shadow: 0 4px 10px rgba(229,9,20,0.3);">
                                Voir le film
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color: #888;">Cet utilisateur n'a pas encore laissé d'avis.</p>
            <?php endif; ?>
        </section>

    </main>

    <footer style="text-align: center; padding: 20px; color: #888;">
        &copy; 2026 - RecoMédia - Projet Shelly-Linda
    </footer>
</body>

</html>