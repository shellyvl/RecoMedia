# PROJET-L2 : RecoMédia

![Bannière du site](<img width="2560" height="1440" alt="logo4" src="https://github.com/user-attachments/assets/3dab1383-be05-411a-9892-3e81384f9a3c" />
)
# RecoMédia : Votre bibliothèque culturelle personnelle

> Une plateforme web dynamique "Full Stack" pour découvrir, partager et noter des œuvres cinématographiques (Films, Séries, Animés).

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=flat&logo=mysql)
![HTML/CSS](https://img.shields.io/badge/UI-Glassmorphism-E34F26?style=flat&logo=html5)
![Status](https://img.shields.io/badge/Status-Terminé-success)

## L'Expérience Utilisateur
Ce projet a été conçu avec une architecture claire séparant les droits d'accès. [cite_start]L'interface, inspirée du *Glassmorphism* sur un thème sombre, propose 3 niveaux d'interactions:

### 1. Le Catalogue Public (Visiteur)
L'aventure débute sur la page d'accueil. Sans même être connecté, vous pouvez parcourir l'ensemble des recommandations de la communauté, filtrer les œuvres via le moteur de recherche dynamique, et lire les critiques détaillées.

### 2. Le Tableau de Bord (Membre)
Une fois inscrit (avec un système de mot de passe haché et sécurisé), le site devient interactif. Vous accédez à un formulaire de saisie dynamique (adaptatif selon le type d'œuvre) pour ajouter vos propres recommandations, publier vos notes (jauge d'étoiles dynamique), et personnaliser votre biographie.

### 3. Le Panel de Supervision (Administrateur)
Un espace protégé, réservé à la modération. L'administrateur possède une vue d'ensemble sur la communauté et peut supprimer des contenus inappropriés ou bannir des membres (déclenchant un nettoyage automatique en cascade dans la base de données).

---
## Galerie
| Page d'Accueil | Tableau de Bord | Fiche Détaillée & Avis |
| :---: | :---: | :---: |
| <img src="images/accueil.jpeg" width="300"> | <img src="images/dashboard.jpeg" width="300"> | <img src="images/detail.jpeg" width="300"> |


## Fonctionnalités Clés
* **Système d'Authentification :** Inscription, connexion et gestion des sessions sécurisées en PHP.
* **Architecture CRUD :** Création, Lecture, Mise à jour et Suppression des œuvres et des commentaires.
* **Moteur de Recherche :** Filtrage en temps réel du catalogue (Titre ou Catégorie).
* **Interface Dynamique :** Formulaires adaptatifs en Vanilla JS et design *Glassmorphism* en CSS3 pur.
* **Sécurité & Modération :** Protection contre les failles (SQLi, XSS), système de droits d'accès (Membre/Admin) et suppressions en cascade.
* **Statistiques en direct :** Calcul et affichage en temps réel de l'implication des membres sur leur profil.

---
## Structure du Projet
[cite_start]Voici comment est organisé le code source:
```text
PROJET-L2/
|-- recomedia.sql             # Script d'exportation de la base de données
|-- connexion.php             # Connexion à la base (MySQLi)
|-- index.php                 # Page d'accueil et catalogue
|
|-- css/                      
|   |-- style.css             # Feuille de style (Variables, Dark Mode)
|
|-- js/                       
|   |-- script.js             # Scripts dynamiques (Formulaires, Bio)
|
|-- images/                   # Assets visuels (Logo, Fonds)
|
|-- pages/                    # Logique métier et vues PHP
    |-- login.php / register.php / logout.php  # Authentification
    |-- dashboard.php / modifier.php           # Espace privé (CRUD)
    |-- profil.php / profil_public.php         # Espace membre et statistiques
    |-- detail.php                             # Fiches œuvres et commentaires
    |-- admin.php / supprimer_user.php         # Administration et bannissement
|
|-- Rapport_Projet_Web.pdf    # Documentation technique complète
