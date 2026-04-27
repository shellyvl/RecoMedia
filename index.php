<?php
// ==========================================
// 1. INITIALISATION ET CONNEXION BDD
// ==========================================
session_start();
include "connexion.php";

// ==========================================
// 2. RECHERCHE ET AFFICHAGE DES CONTENUS
// ==========================================
// Par défaut, on sélectionne tout (trié par le plus récent)
$sql = "SELECT * FROM contenus ORDER BY id DESC";

// Si une recherche a été validée dans la barre de recherche :
if (isset($_GET['recherche']) && !empty($_GET['recherche'])) {
    $recherche = mysqli_real_escape_string($conn, $_GET['recherche']);
    // Recherche par titre OU par type (film, série, animé) 
    $sql = "SELECT * FROM contenus WHERE titre LIKE '%$recherche%' OR type LIKE '%$recherche%'";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>RecoMédia</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

    <header class="main-header">
        <h1>
            <a href="index.php" class="logo-link">
                <img src="images/logo4.png" alt="Logo" class="logo-img">
                RecoMédia
            </a>
        </h1>
        <p class="header-subtitle">Votre bibliothèque culturelle personnelle</p>
    </header>

    <nav>

        <a href="index.php"><i class="fas fa-home"></i> Accueil</a>

        <?php if (isset($_SESSION['pseudo'])): ?>
            <a href="pages/dashboard.php"><i class="fas fa-clapperboard"></i> Tableau de Bord</a>
            <a href="pages/profil.php"><i class="fas fa-user"></i> Mon Profil</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="pages/admin.php" style="color: #ffcc00;"><i class="fas fa-user-shield"></i> Panel Admin</a>
            <?php endif; ?>
            <a href="pages/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        <?php else: ?>
            <a href="pages/login.php"><i class="fas fa-sign-in-alt"></i> Connexion</a>
            <a href="pages/register.php"><i class="fas fa-user-plus"></i> Inscription</a>
        <?php endif; ?>

    </nav>

    <main>
        <div class="container">

            <section class="hero-header">
                <div class="bienvenue-text">
                    <h2>Bienvenue sur RecoMédia</h2>
                    <p>Vous ne savez pas quoi visionner ? RecoMédia vous aide à faire votre choix.
                        Découvrez de nouveaux films, séries et animés et partagez votre avis !</p>
                    <p><strong>Votre prochaine découverte commence ici.</strong></p>
                </div>

                <div class="search-box">
                    <form method="GET" action="index.php">
                        <div class="search-input-wrapper">
                            <input type="text" name="recherche" placeholder="Rechercher..."
                                value="<?php echo isset($_GET['recherche']) ? htmlspecialchars($_GET['recherche']) : ''; ?>">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </div>

                        <?php if (isset($_GET['recherche']) && !empty($_GET['recherche'])): ?>
                            <a href="index.php" class="clear-search">
                                <i class="fas fa-times"></i> Effacer
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </section>

            <h2 class="section-title">Contenus récents</h2>

            <div class="grid-container">
                <?php
                // Vérification s'il y a des résultats dans la BDD
                if (mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)):
                        ?>
                        <article class="card">
                            <div class="card-badge"><?php echo ucfirst($row['type']); ?></div>

                            <div class="card-image-container">
                                <img src="<?php echo $row['image']; ?>" class="card-img"
                                    alt="Affiche <?php echo htmlspecialchars($row['titre']); ?>">
                            </div>

                            <div class="card-content">
                                <h3><?php echo $row['titre']; ?></h3>

                                <div class="note-container">
                                    <span class="stars">
                                        <?php
                                        for ($i = 0; $i < $row['note']; $i++) {
                                            echo "★";
                                        }
                                        for ($i = $row['note']; $i < 5; $i++) {
                                            echo "☆";
                                        }
                                        ?>
                                    </span>
                                    <span class="rating-value"><?php echo $row['note']; ?>/5</span>
                                </div>

                                <a href="pages/detail.php?id=<?php echo $row['id']; ?>" class="btn-minimal">
                                    Voir les détails <span>&rarr;</span>
                                </a>
                            </div>
                        </article>
                        <?php
                    endwhile;
                else:
                    ?>
                    <p style="text-align: center; width: 100%; color: #aaa;">Aucun contenu trouvé. Ajoutez votre premier
                        film !</p>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <footer style="text-align: center; padding: 20px; color: #888;">
        &copy; 2026 - RecoMédia - Projet Shelly-Linda
    </footer>
    <script src="js/script.js"></script>
</body>

</html>