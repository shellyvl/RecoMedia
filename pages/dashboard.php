<?php
session_start();
include "../connexion.php";

// ==========================================
// 1.SÉCURITÉ : Vérification de la connexion
// ==========================================
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id_utilisateur = $_SESSION['user_id'];

// ==========================================
// 2. LOGIQUE D'ENREGISTREMENT (AJOUT) ---
// ==========================================

if (isset($_POST['valider_ajout'])) {
    $titre = mysqli_real_escape_string($conn, $_POST['titre']);
    $type = $_POST['type'];
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $annee = (int) $_POST['annee'];
    $duree = mysqli_real_escape_string($conn, $_POST['duree']);

    $saisons = isset($_POST['saisons']) ? (int) $_POST['saisons'] : 0;
    $episodes = isset($_POST['episodes']) ? (int) $_POST['episodes'] : 0;

    $image = mysqli_real_escape_string($conn, $_POST['image']);
    $synopsis = mysqli_real_escape_string($conn, $_POST['synopsis']);
    $note = (int) $_POST['note'];

    $sql = "INSERT INTO contenus (titre, type, genre, annee, duree, nb_saisons, nb_episodes, image, synopsis, note, id_utilisateur) 
            VALUES ('$titre', '$type', '$genre', '$annee', '$duree', '$nb_saisons', '$nb_episodes', '$image', '$synopsis', '$note', '$id_utilisateur')";

    if (mysqli_query($conn, $sql)) {
        header("Location: dashboard.php?msg=ajoute");
    }
}

// ==================================================
// 3. RÉCUPÉRATION DES CONTENUS DE L'UTILISATEUR 
// ==================================================
// $mon_id = $_SESSION['user_id'];
// $result = mysqli_query($conn, "SELECT * FROM contenus WHERE id_utilisateur = '$mon_id'");
// 
$query = "SELECT * FROM contenus WHERE id_utilisateur = $id_utilisateur ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - RecoMédia</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

    <header class="main-header">
        <h1><i class="fas fa-clapperboard"></i> Mon Tableau de Bord</h1>
    </header>

    <nav>
        <a href="../index.php"><i class="fas fa-home"></i> Accueil</a>
        <a href="profil.php"><i class="fas fa-user-circle"></i> Mon Profil</a>
        <a href="logout.php" style="color: #ff4d4d;"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </nav>

    <main class="container">

        <h2 class="dashboard-section-title">Gestion de mes recommandations</h2>

        <section class="glass-section">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Affiche</th>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Année</th>
                        <th>Note</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><img src="<?php echo $row['image']; ?>" width="50" style="border-radius: 5px;"
                                    alt="Affiche"></td>
                            <td style="font-weight: bold;"><?php echo htmlspecialchars($row['titre']); ?></td>
                            <td><span class="badge-type"><?php echo ucfirst($row['type']); ?></span></td>
                            <td><?php echo $row['annee']; ?></td>
                            <td style="color: var(--accent-gold);">
                                <?php echo $row['note']; ?>/5
                            </td>
                            <td>
                                <div class="action-group">
                                    <a href="modifier.php?id=<?php echo $row['id']; ?>" class="action-btn edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="supprimer.php?id=<?php echo $row['id']; ?>" class="action-btn delete"
                                        onclick="return confirm('Supprimer ?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>

        <section id="ajout-direct" class="glass-section">
            <h3><i class="fas fa-plus-circle"></i> Ajouter un contenu</h3>

            <form action="dashboard.php" method="POST" class="add-form">
                <div class="form-row">
                    <input type="text" name="titre" placeholder="Titre..." required>
                    <select name="type" id="type_select" onchange="toggleInputs()">
                        <option value="film">Film</option>
                        <option value="série">Série</option>
                        <option value="animé">Animé</option>
                    </select>
                </div>

                <div class="form-row">
                    <input type="text" name="genre" placeholder="Genre (Action, Drame...)">
                    <input type="number" name="année" placeholder="Année de sortie">
                </div>

                <div id="serie_inputs" style="display: none; gap: 10px; margin-top: 10px;">
                    <input type="number" name="saisons" placeholder="Nombre de saisons" min="0">
                    <input type="number" name="episodes" placeholder="Nombre d'épisodes" min="0">
                </div>

                <div class="form-row">
                    <input type="text" name="duree" placeholder="Durée (ex: 2h 15min)">
                    <input type="number" name="note" placeholder="Note / 5" min="1" max="5">
                </div>

                <input type="text" name="image" placeholder="Lien de l'image (URL)...">
                <textarea name="synopsis" placeholder="Synopsis..." required></textarea>

                <button type="submit" name="valider_ajout" class="btn-submit">
                    <i class="fas fa-save"></i> Enregistrer dans ma bibliothèque
                </button>
            </form>
        </section>
    </main>

    <footer style="text-align: center; padding: 20px; color: #888;">
        &copy; 2026 - RecoMédia - Projet Shelly-Linda
    </footer>
    <script src="../js/script.js"></script>
</body>

</html>