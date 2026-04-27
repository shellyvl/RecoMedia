<?php
// ==========================================
// 1. INITIALISATION ET CONNEXION
// ==========================================
session_start();
include "../connexion.php";

// On vérifie qu'un ID de contenu est bien présent dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ==========================================
    // 2. GESTION DES ACTIONS (CRUD COMMENTAIRES)
    // ==========================================.

    // --- ACTION : SUPPRIMER UN COMMENTAIRE ---
    if (isset($_GET['supprimer_com'])) {
        $id_com_a_supprimer = $_GET['supprimer_com'];
        $sql_delete = "DELETE FROM commentaires WHERE id = $id_com_a_supprimer";
        mysqli_query($conn, $sql_delete);
        header("Location: detail.php?id=$id");
        exit();
    }

    // --- ACTION : SAUVEGARDER UNE MODIFICATION ---
    if (isset($_POST['sauvegarder_modification'])) {
        $id_com_a_modifier = $_POST['id_com'];
        $nouveau_texte = mysqli_real_escape_string($conn, $_POST['texte_modifie']);
        $nouvelle_note = $_POST['note_modifiee'];
        
        $sql_update = "UPDATE commentaires SET texte = '$nouveau_texte', note = $nouvelle_note WHERE id = $id_com_a_modifier";
        mysqli_query($conn, $sql_update);
        header("Location: detail.php?id=$id");
        exit();
    }

    // --- ACTION : AJOUTER UN NOUVEAU COMMENTAIRE ---
    if (isset($_POST['ajouter_commentaire'])) {
        $pseudo_auteur = $_SESSION['pseudo'];
        $texte = mysqli_real_escape_string($conn, $_POST['texte']);
        $note = $_POST['note'];

        $sql_insert = "INSERT INTO commentaires (id_contenu, pseudo, texte, note) 
                       VALUES ($id, '$pseudo_auteur', '$texte', $note)";

        mysqli_query($conn, $sql_insert);
        header("Location: detail.php?id=$id");
        exit();
    }
    
    // ==========================================
    // 3. RÉCUPÉRATION DES DONNÉES DU CONTENU
    // ==========================================
    $sql = "SELECT contenus.*, utilisateurs.pseudo AS pseudo_auteur, utilisateurs.id AS id_auteur 
            FROM contenus 
            JOIN utilisateurs ON contenus.id_utilisateur = utilisateurs.id 
            WHERE contenus.id=$id";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
    } else {
        header("Location: ../index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails - <?php echo $data['titre']; ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <header style="text-align: center; margin-bottom: 30px; padding-top: 20px;">
        <h1 style="margin: 0; padding: 0;">
            <a href="../index.php" style="text-decoration: none; color: white; position: relative; display: inline-block;">
                <img src="../images/logo4.png" alt="Logo" style="height: 60px; position: absolute; right: 100%; margin-right: 20px; top: 50%; transform: translateY(-50%);">
                RecoMédia
            </a>
        </h1>
        <p style="color: #bbb; margin-top: 15px; font-style: italic;">Votre bibliothèque culturelle personnelle</p>
    </header>

    <nav>
        <a href="../index.php"><i class="fas fa-home"></i> Accueil</a>
        <?php if (isset($_SESSION['pseudo'])): ?>
            <a href="profil.php"><i class="fas fa-user-circle"></i> Mon Profil</a>
            <a href="logout.php" style="color: #ff4d4d;"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        <?php else: ?>
            <a href="login.php"><i class="fas fa-sign-in-alt"></i> Connexion</a>
            <a href="register.php"><i class="fas fa-user-plus"></i> Inscription</a>
        <?php endif; ?>
    </nav>

    <main class="container">
        <article class="detail-container">

            <section class="main-info">
                <h2 class="section-title" style="text-align: left; margin-top: 0;">
                    <?php echo $data['titre']; ?>
                </h2>
                
                <div class="content-layout">
                    <div class="media-poster">
                        <?php if (!empty($data['image'])): ?>
                            <img src="<?php echo $data['image']; ?>" alt="Affiche">
                        <?php else: ?>
                            <div class="no-image"><span>Pas d'affiche</span></div>
                        <?php endif; ?>
                    </div>

                    <div class="synopsis">
                        <h3>Synopsis</h3>
                        <p><?php echo nl2br($data['synopsis']); ?></p>

                        <div class="movie-meta">
                            <span><?php echo $data['annee']; ?></span> |
                            <span><?php echo $data['genre']; ?></span> |
                            <span><?php echo $data['duree']; ?></span>

                            <?php if ($data['type'] !== 'film'): ?>
                                | <span><?php echo $data['nb_saisons']; ?> Saisons</span>
                                | <span><?php echo $data['nb_episodes']; ?> Épisodes</span>
                            <?php endif; ?>

                            <br><br>
                            
                            <?php
                                $lien_profil = (isset($_SESSION['pseudo']) && $_SESSION['pseudo'] === $data['pseudo_auteur']) 
                                                ?"profil.php"
                                                :"profil_public.php?id=" . $data['id_auteur'];
                            ?>
                            <span style="color: #ccc;">Proposé par :
                                <a href="<?php echo $lien_profil; ?>"   class="author-link">
                                    @<?php echo $data['pseudo_auteur']; ?>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="comments-section">
                <h3 style="color: var(--primary); margin-top: 0;">Avis de la communauté</h3>

                <div id="ListeCommentaire">
                    <?php
                    $sql_com = "SELECT commentaires.*, utilisateurs.id AS id_commentateur 
                            FROM commentaires 
                            JOIN utilisateurs ON commentaires.pseudo = utilisateurs.pseudo 
                            WHERE commentaires.id_contenu = $id 
                            ORDER BY commentaires.id DESC";
                    $result_com = mysqli_query($conn, $sql_com);

                    while ($com = mysqli_fetch_assoc($result_com)):
                        $lien_profil_com = (isset($_SESSION['pseudo']) && $_SESSION['pseudo'] === $com['pseudo'])
                            ?"profil.php"
                            : "profil_public.php?id=" . $com['id_commentateur'];
                    ?>
                        
                        <div class="comment">
                            <?php if (isset($_GET['edit_com']) && $_GET['edit_com'] == $com['id']): ?>
                                <form method="POST" action="detail.php?id=<?php echo $id; ?>">
                                    <label style="color: #fff9f9; font-weight: bold;">Modifier la note :</label>
                                    <input type="number" name="note_modifiee" min="1" max="5" value="<?php echo $com['note']; ?>" style="width: 70px;"> / 5
                                    <textarea name="texte_modifie"><?php echo htmlspecialchars($com['texte']); ?></textarea>
                                    <input type="hidden" name="id_com" value="<?php echo $com['id']; ?>">
                                    <button type="submit" name="sauvegarder_modification" style="padding: 8px 15px;">Enregistrer</button>
                                    <a href="detail.php?id=<?php echo $id; ?>">Annuler</a>
                                    </form>
                            <?php else: ?>
                                <p>
                                    <strong><a href="<?php echo $lien_profil_com; ?>">@<?php echo $com['pseudo']; ?></a></strong>
                                    <span class="stars">
                                        <?php echo str_repeat("★", $com['note']) . str_repeat("☆", 5-$com['note']); ?>
                                    </span>
                                </p>

                                <p class="com-text"><?php echo nl2br(htmlspecialchars($com['texte'])); ?></p>

                                <?php if (isset($_SESSION['pseudo']) && $_SESSION['pseudo'] == $com['pseudo']):?>
                                    <div class="com--actions">
                                        <a href="detail.php?id=<?php echo $id; ?>&edit_com=<?php echo $com['id']; ?>" class="edit"><i class="fas fa-edit"></i> Modifier</a>
                                        <a href="detail.php?id=<?php echo $id; ?>&supprimer_com=<?php echo $com['id']; ?>" class="delete" onclick="return confirm('Voulez-vous vraiment supprimer cet avis ?');"><i class="fas fa-trash"></i> Supprimer</a>
                                    </div>
                                <?php endif; ?>
                            <?php endif ?>
                        </div>
                    <?php endwhile;?>
                </div>

                <?php if (isset($_SESSION['pseudo'])): ?>
                    <h3 class="add-title">Ajouter votre avis</h3>
                    <form method="POST" action="detail.php?id=<?php echo $id; ?>">
                        <label>Note :</label>
                        <input type="number" name="note" min="1" max="5" value="5" style="width: 60px;"> / 5
                        <textarea name="texte" placeholder="Votre avis..." required style="height: 80px;"></textarea>
                        <button type="submit" name="ajouter_commentaire"style="margin-top: 10px; width: 100%;">Publier</button>
                    </form>
                <?php endif; ?>
            </section>

        </article>
    </main>

    <footer style="text-align: center; padding: 20px; color: #888;">
        &copy; 2026 - RecoMédia - Projet Shelly-Linda
    </footer>
</body>
</html>