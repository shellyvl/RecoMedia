<?php
// ==========================================
// 1. INITIALISATION ET SÉCURITÉ
// ==========================================
session_start();
include "../connexion.php";

// Si l'utilisateur n'est pas connecté, on le renvoie à l'accueil
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$id_session = $_SESSION['user_id'];
$mon_pseudo = mysqli_real_escape_string($conn, $_SESSION['pseudo']);

// ==========================================
// 2. MODIFICATION DE LA BIO
// ==========================================

if (isset($_POST['update_bio'])) {
    $nouvelle_bio = mysqli_real_escape_string($conn, $_POST['bio_text']);
    mysqli_query($conn, "UPDATE utilisateurs SET bio = '$nouvelle_bio' WHERE id = $id_session");
    // Redirection pour rafraîchir l'affichage après modification
    header("Location: profil.php");
    exit();
}

// ==========================================
// 3. RÉCUPÉRATION DES DONNÉES DU PROFIL
// ==========================================

// A. Les infos de l'utilisateur (pour la bio)
$sql_user = "SELECT * FROM utilisateurs WHERE id = $id_session";
$res_user = mysqli_query($conn, $sql_user);
$user_data = mysqli_fetch_assoc($res_user);
$ma_bio = !empty($user_data['bio']) ? $user_data['bio'] : "Membre passionnée de cinéma";

// B. Les recommandations de l'utilisateur
$query_recos = "SELECT * FROM contenus WHERE id_utilisateur = $id_session ORDER BY id DESC";
$result_recos = mysqli_query($conn, $query_recos);

// C. Compteurs ( recos et avis)
$sql_mes_recos = "SELECT COUNT(*) AS total FROM contenus WHERE id_utilisateur = (SELECT id FROM utilisateurs WHERE pseudo = '$mon_pseudo')";
$total_recos = mysqli_fetch_assoc(mysqli_query($conn, $sql_mes_recos))['total'];

$sql_mes_avis = "SELECT COUNT(*) AS total FROM commentaires WHERE pseudo = '$mon_pseudo'";
$total_avis = mysqli_fetch_assoc(mysqli_query($conn, $sql_mes_avis))['total'];

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Profil - <?php echo htmlspecialchars($mon_pseudo); ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

    <header class="main-header">
        <h1>
            <i class="fas fa-user"></i> Mon Profil
        </h1>
    </header>

    <nav>
        <a href="../index.php"><i class="fas fa-home"></i> Accueil</a>
        <a href="dashboard.php"><i class="fas fa-clapperboard"></i> Tableau de Bord</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </nav>

    <main class="container">

        <section class="glass-section profile-header">
            <div class="profile-avatar">
                <?php echo strtoupper($mon_pseudo[0]); ?>
            </div>

            <div class="profile-info">
                <div style="flex: 1; padding-right: 20px;">
                    <h2 style="margin:0; color: white;"><?php echo htmlspecialchars($mon_pseudo); ?></h2>

                    <div id="bio-display">
                        <p class="bio-text">
                            "<?php echo htmlspecialchars($ma_bio); ?>"
                            <i class="fas fa-pen bio-edit-icon" onclick="toggleEditBio()" title="Modifier ma bio"></i>
                        </p>
                    </div>

                    <div id="bio-form" style="display: none; margin-top: 10px;">
                        <form method="POST">
                            <textarea name="bio_text" rows="2"
                                class="bio-textearea"><?php echo htmlspecialchars($ma_bio); ?></textarea>
                            <div>
                                <button type="submit" name="update_bio" style="padding: 8px 15px; font-size: 0.85rem;">Enregistrer</button>
                                <button type="button" onclick="toggleEditBio()" class="btn-cancel">Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="profile-stats">
                    <div>
                        <span class="stat-number"><?php echo $total_recos; ?></span>
                        <span class="stat-label" >Recos</span> 
                    </div>
                    <div>
                        <span class="stat-number"><?php echo $total_avis; ?></span>
                        <span class="stat-label">Avis postés</span>
                    </div>
                </div>

            </div>
        </section>

        <section class="glass-section">
            <h2 class="section-title" style="text-align: left;">Mes recommandations</h2>

            <?php if (mysqli_num_rows($result_recos) > 0): ?>
                <div class="recommandations-container">
                    <?php while ($row = mysqli_fetch_assoc($result_recos)): ?>
                        <a href="detail.php?id=<?php echo $row['id']; ?>" style="text-decoration: none; display: block;">
                            <div class="reco-card">
                                <img src="<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['titre']); ?>">
                                <h3><?php echo htmlspecialchars($row['titre']); ?></h3>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 50px;">
                    <p style="color: #bbb;">Tu n'as pas encore ajouté de recommandations.</p>
                    <a href="dashboard.php" style="color: var(--primary); text-decoration: underline;">Ajouter mon premier
                        film</a>
                </div>
            <?php endif; ?>
        </section>

        <section class="glass-section">
            <h2 class="section-title" style="text-align: left;">Mes derniers avis postés</h2>

            <?php
            // On récupère les 3 derniers commentaires de l'utilisateur
            $sql_derniers_coms = "SELECT commentaires.*, contenus.titre 
                                  FROM commentaires 
                                  JOIN contenus ON commentaires.id_contenu = contenus.id 
                                  WHERE commentaires.pseudo = '$mon_pseudo'
                                  ORDER BY commentaires.id DESC LIMIT 3";

            $result_derniers_coms = mysqli_query($conn, $sql_derniers_coms);

            if ($result_derniers_coms && mysqli_num_rows($result_derniers_coms) > 0) :

                while ($com = mysqli_fetch_assoc($result_derniers_coms)):
            ?>
                    <div class="comment-card">
                        <div style="flex: 1;">
                            <div class="stars">
                                <?php echo str_repeat("★", $com['note']) . str_repeat("☆", 5 - $com['note']); ?>
                            </div>

                             <p style="margin: 0;">
                                <em>"<?php echo nl2br(htmlspecialchars($com['texte'])); ?>"</em>
                                <br>
                                <span style="font-size: 0.8rem; color: #888;">
                                    Sur le contenu : <strong><?php echo htmlspecialchars($com['titre']); ?></strong>
                                </span>
                            </p>
                        </div>

                        <div>
                            <a href="detail.php?id=<?php echo $com['id_contenu']; ?>"class="btn-view">
                                Voir <i class="fas fa-arrow-right" style="margin-left: 5px;"></i>
                            </a>
                        </div>
                    </div>
            <?php
                endwhile;
            else:
            ?>
                <p style="color: #888;"><em>Vous n'avez pas encore posté de commentaire.</em></p>
            <?php endif; ?>    
        </section>

    </main>

    <footer style="text-align: center; padding: 20px; color: #888;">
        &copy; 2026 - RecoMédia - Projet Shelly-Linda
    </footer>
    <script src="../js/script.js"></script>
</body>

</html>