<?php
session_start();
include "../connexion.php";

// 1. On récupère le film actuel (avec les nouveaux champs !)
$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM contenus WHERE id=$id");
$data = mysqli_fetch_assoc($query);

// 2. Si on clique sur "Enregistrer les modifications"
if(isset($_POST['modifier'])) {
    $titre = mysqli_real_escape_string($conn, $_POST['titre']);
    $type = $_POST['type'];
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $annee = (int)$_POST['annee'];
    $duree = mysqli_real_escape_string($conn, $_POST['duree']);
    $nb_saisons = isset($_POST['nb_saisons']) ? (int)$_POST['nb_saisons'] : 0;
    $nb_episodes = isset($_POST['nb_episodes']) ? (int)$_POST['nb_episodes'] : 0;
    $image = mysqli_real_escape_string($conn, $_POST['image']);
    $synopsis = mysqli_real_escape_string($conn, $_POST['synopsis']);
    $note = (int)$_POST['note'];

    // La requête UPDATE complète
    $sql = "UPDATE contenus SET 
            titre='$titre', 
            type='$type', 
            genre='$genre', 
            annee='$annee', 
            duree='$duree', 
            nb_saisons='$nb_saisons', 
            nb_episodes='$nb_episodes', 
            image='$image', 
            synopsis='$synopsis', 
            note='$note' 
            WHERE id=$id";
    
    if(mysqli_query($conn, $sql)) {
        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier - <?php echo $data['titre']; ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <h1><i class="fas fa-edit"></i> Modifier le contenu</h1>
    </header>

    <main class="container">
        <section class="glass-section" style="max-width: 800px; margin: 40px auto;">
            
            <h2 style="color: var(--primary); margin-top: 0; text-align: center;">
                <?php echo $data['titre']; ?>
            </h2>

            <form method="POST">
                <label style="color: #ccc; font-weight: bold;">Titre :</label>
                <input type="text" name="titre" value="<?php echo $data['titre']; ?>" required>

                <div style="display: flex; gap: 20px; margin-top: 15px;">
                    <div style="flex: 1;">
                        <label style="color: #ccc; font-weight: bold;">Type :</label>
                        <select name="type" id="type_select" onchange="toggleInputs()">
                            <option value="film" <?php if($data['type'] == 'film') echo 'selected'; ?>>Film</option>
                            <option value="serie" <?php if($data['type'] == 'serie') echo 'selected'; ?>>Série</option>
                            <option value="anime" <?php if($data['type'] == 'anime') echo 'selected'; ?>>Animé</option>
                        </select>
                    </div>
                    <div style="flex: 1;">
                        <label style="color: #ccc; font-weight: bold;">Genre :</label>
                        <input type="text" name="genre" value="<?php echo $data['genre']; ?>" placeholder="Action, Horreur...">
                    </div>
                </div>

                <div style="display: flex; gap: 20px; margin-top: 15px;">
                    <div style="flex: 1;">
                        <label style="color: #ccc; font-weight: bold;">Année :</label>
                        <input type="number" name="annee" value="<?php echo $data['annee']; ?>">
                    </div>
                    <div style="flex: 1;">
                        <label style="color: #ccc; font-weight: bold;">Durée :</label>
                        <input type="text" name="duree" value="<?php echo $data['duree']; ?>" placeholder="Ex: 2h ou 24min">
                    </div>
                </div>

                <div id="extra_fields" style="display: <?php echo ($data['type'] != 'film') ? 'block' : 'none'; ?>; background: rgba(0,0,0,0.3); padding: 15px; border-radius: 8px; border: 1px solid #444; margin-top: 15px;">
                    <div style="display: flex; gap: 20px;">
                        <div style="flex: 1;">
                            <label style="color: #ccc; font-weight: bold;">Nombre de saisons :</label>
                            <input type="number" name="nb_saisons" value="<?php echo $data['nb_saisons']; ?>">
                        </div>
                        <div style="flex: 1;">
                            <label style="color: #ccc; font-weight: bold;">Nombre d'épisodes :</label>
                            <input type="number" name="nb_episodes" value="<?php echo $data['nb_episodes']; ?>">
                        </div>
                    </div>
                </div>

                <div style="margin-top: 15px;">
                    <label style="color: #ccc; font-weight: bold;">Lien de l'image (URL) :</label>
                    <div style="display: flex; gap: 15px; align-items: flex-start; margin-top: 8px;">
                        <div style="flex: 1;">
                            <input type="text" name="image" value="<?php echo $data['image']; ?>" placeholder="Collez le nouveau lien ici...">
                        </div>
                        <?php if(!empty($data['image'])): ?>
                            <div style="text-align: center;">
                                <img src="<?php echo $data['image']; ?>" alt="Preview" style="width: 60px; height: 90px; object-fit: cover; border-radius: 6px; border: 1px solid #444;">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div style="margin-top: 15px;">
                    <label style="color: #ccc; font-weight: bold;">Synopsis :</label>
                    <textarea name="synopsis" rows="5"><?php echo $data['synopsis']; ?></textarea>
                </div>

                <div style="margin-top: 15px;">
                    <label style="color: #ccc; font-weight: bold;">Note (sur 5) :</label>
                    <input type="number" name="note" min="1" max="5" value="<?php echo $data['note']; ?>" style="width: 100px;">
                </div>

                <div style="display: flex; gap: 15px; margin-top: 30px; align-items: center;">
                    <button type="submit" name="modifier" class="btn-submit" style="flex: 2;">Enregistrer les modifications</button>
                    <a href="dashboard.php" style="flex: 1; text-align: center; color: #bbb; text-decoration: none; padding: 12px; border: 1px solid #444; border-radius: 30px; transition: 0.3s;">Annuler</a>
                </div>
            </form>
        </section>
    </main>

    <script>
    function toggleInputs() {
        var type = document.getElementById("type_select").value;
        var extraFields = document.getElementById("extra_fields");
        extraFields.style.display = (type === "serie" || type === "anime") ? "block" : "none";
    }
    </script>
</body>
</html>