<?php
session_start();
include "../connexion.php";

$erreur = "";

if (isset($_POST['connexion'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mdp = $_POST['password'];

    $sql = "SELECT * FROM utilisateurs WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($user = mysqli_fetch_assoc($result)) {
        // On vérifie le mot de passe haché
        if (password_verify($mdp, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['pseudo'] = $user['pseudo'];
            $_SESSION['role'] = $user['role'];
            
            header("Location: profil.php");
            exit();
        } else {
            $erreur = "Mot de passe incorrect.";
        }
    } else {
        $erreur = "Aucun compte trouvé avec cet email.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion - RecoMédia</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <header>
        <h1><i class="fas fa-lock"></i> Connexion</h1>
    </header>

    <main>
        <div class="auth-container">
            <!-- <h2 class="section-title">Connexion</h2> -->

            <?php if (isset($_GET['success'])): ?>
                <p
                    style="color: #28a745; background: #d4edda; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 15px;">
                    Votre compte a été créé avec succès ! Connectez-vous.
                </p>
            <?php endif; ?>
            <?php if (isset($_GET['deconnecte'])): ?>
                <p
                    style="color: #666; background: #e2e3e5; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 15px;">
                    Vous avez été déconnecté avec succès. À bientôt !
                </p>
            <?php endif; ?>
            <form method="POST" action="">
                <label>Email :</label>
                <input type="email" name="email" placeholder="votre@email.com" required>

                <label>Mot de passe :</label>
                <input type="password" name="password" placeholder="********" required>

                <button type="submit" name="connexion">Se connecter</button>
            </form>

            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">

            <p style="text-align: center; margin-top: 20px;">
                <span style="color: #bbb;">Pas encore de compte ?</span>
                <a href="register.php" class="auth-link-white">S'inscrire ici</a>
            </p>
            <p style="text-align: center;">
                <a href="../index.php" style="text-decoration: none; color: inherit;">
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