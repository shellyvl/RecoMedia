<?php
session_start();
// On inclut la connexion à la base de données [cite: 41, 53]
include "../connexion.php";

$message = "";

// On vérifie si le formulaire a été envoyé
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // On récupère et on sécurise les données [cite: 43]
    $pseudo = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mdp = $_POST['password'];
    $confirm_mdp = $_POST['confirm_password'];

    // Vérification : les mots de passe sont-ils identiques ?
    if ($mdp === $confirm_mdp) {
        // On hache le mot de passe pour la sécurité
        $mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);
        
        // Requête pour insérer le nouvel utilisateur dans la table [cite: 23, 43]
        $sql = "INSERT INTO utilisateurs (pseudo, email, mot_de_passe) VALUES ('$pseudo', '$email', '$mdp_hache')";
        
        if (mysqli_query($conn, $sql)) {
            // Si ça marche, on redirige vers la connexion avec un message de succès [cite: 24, 33]
            header("Location: login.php?success=1");
            exit();
        } else {
            $message = "Erreur : L'email est peut-être déjà utilisé.";
        }
    } else {
        $message = "Les mots de passe ne correspondent pas.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - RecoMédia</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <h1><i class="fas fa-user-plus"></i> Créer un compte</h1>
    </header>

    <main>
        <div class="auth-container">
            <?php if($message != ""): ?>
                <p style="color: #ff4d4d; text-align: center; margin-bottom: 15px;"><?php echo $message; ?></p>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <div style="margin-bottom: 15px;">
                    <label>Pseudo :</label>
                    <input type="text" name="username" placeholder="Ex: Shelly-Linda" required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label>Email :</label>
                    <input type="email" name="email" placeholder="votre@email.com" required>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label>Mot de passe :</label>
                    <input type="password" name="password" placeholder="********" required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label>Confirmer le mot de passe :</label>
                    <input type="password" name="confirm_password" placeholder="********" required>
                </div>
                
                <button type="submit">S'inscrire</button>
            </form>
            
            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #444;">
            
            <p style="text-align: center;">
                <span style="color: #bbb;">Déjà membre ?</span> 
                <a href="login.php" class="auth-link-white"> Se connecter</a>
            </p>
            <p style="text-align: center;">
                <a href="../index.php" class="auth-link-white">
                   <i class="fas fa-arrow-left"></i> Retour à l'accueil
                </a>
            </p>
        </div>
    </main>

<footer style="text-align: center; padding: 20px; color: #888;">
    &copy; 2026 - RecoMédia - Projet Shelly-Linda
</footer>
</body>
</html>