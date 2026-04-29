-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : mer. 29 avr. 2026 à 19:25
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `recomedia`
--

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE `commentaires` (
  `id` int(11) NOT NULL,
  `id_contenu` int(11) NOT NULL,
  `pseudo` varchar(100) DEFAULT '',
  `texte` text NOT NULL,
  `note` int(11) NOT NULL DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentaires`
--

INSERT INTO `commentaires` (`id`, `id_contenu`, `pseudo`, `texte`, `note`) VALUES
(12, 5, 'Shelly', 'cool!!hehehe', 5),
(13, 6, 'Shelly', 'super', 5),
(14, 5, 'Emy', 'incroyable!', 5),
(15, 8, 'Emy', 'J\'aime beaucoup', 5),
(17, 9, 'Shelly', 'masterpiece!!\r\n', 5);

-- --------------------------------------------------------

--
-- Structure de la table `contenus`
--

CREATE TABLE `contenus` (
  `id` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `synopsis` text NOT NULL,
  `note` int(11) NOT NULL,
  `genre` varchar(255) NOT NULL,
  `annee` int(11) NOT NULL,
  `duree` varchar(255) NOT NULL,
  `nb_saisons` int(11) DEFAULT NULL,
  `nb_episodes` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contenus`
--

INSERT INTO `contenus` (`id`, `id_utilisateur`, `titre`, `type`, `image`, `synopsis`, `note`, `genre`, `annee`, `duree`, `nb_saisons`, `nb_episodes`) VALUES
(5, 1, 'My Hero Academia', 'anime', 'https://manganim.fr/cdn/shop/files/my-hero-academia-poster-heros_2048x.jpg?v=1684800592', 'Dans un futur proche suite à une mutation génétique, 80% de la population mondiale possède des super-pouvoirs appelés \"Alters\". Le plus célèbre des super-héros se nomme All Might. Izuku Midoriya en est fan, et rêve d\'intégrer la filière super-héroïque du lycée Yuei pour suivre les traces de son idole.', 5, 'Shonen', 2016, '78 h 16min', 8, 170),
(6, 1, 'inception', 'film', 'https://fr.web.img2.acsta.net/medias/nmedia/18/72/34/14/19476654.jpg', 'Dom Cobb est un voleur expérimenté dans l\'art périlleux de l\'extraction : sa spécialité consiste à s\'approprier les secrets les plus précieux d\'un individu, enfouis au plus profond de son subconscient, pendant qu\'il rêve et que son esprit est particulièrement vulnérable. Très recherché pour ses talents dans l\'univers trouble de l\'espionnage industriel, Cobb est aussi devenu un fugitif traqué dans le monde entier. Cependant, une ultime mission pourrait lui permettre de retrouver sa vie d\'avant.', 4, '', 0, '', 0, 0),
(7, 3, 'La Créature de Kyŏngsŏng', 'série', 'https://image.tmdb.org/t/p/w500/3YjJDK9wQIXYTz42kWTUMAj8DTQ.jpg', 'Au printemps 1945 à Gyeongseong, pendant l\'occupation japonaise de la Corée, Jang Tae-sang et Yoon Chae-ok affrontent une étrange créature née de l\'avidité de l\'Homme et luttent contre elle pour survivre.', 5, '', 0, '', NULL, NULL),
(8, 3, 'XO, Kitty', 'série', 'https://fr.web.img6.acsta.net/pictures/23/05/17/15/00/5722121.jpg', 'L\'histoire d\'amour longue distance de Kitty, ado entremetteuse, rebondit quand elle rejoint son petit ami dans le lycée de Séoul que sa défunte mère avait fréquenté. Katherine fera donc plusieurs découvertes sur sa mère. Elle se rend alors compte qu\'elle ne connaissait pas grand chose à l\'amour – du moins, pas autant qu\'elle ne le croyait.', 5, '', 0, '', NULL, NULL),
(9, 4, 'The Penthouse', 'série', 'https://fr.web.img6.acsta.net/c_310_420/pictures/22/08/17/10/39/0553963.jpg', 'Penthouse raconte l\'histoire de familles riches vivant à Hera Palace et de leurs enfants à l\'école des arts Cheong-ah. Sim Su-ryeon est une femme élégante et riche qui a un passé tragique. Son mari est Joo Dan-tae (Uhm Ki-joon), un homme d\'affaires prospère. Elle apprend plus tard qu\'il lui cache quelque chose.', 5, '', 0, '', NULL, NULL),
(10, 1, 'weak hero class', 'serie', 'https://upload.wikimedia.org/wikipedia/en/3/3e/Weak_Hero_Class_1.jpeg', 'Yeon Si Eun est un étudiant modèle. Il est le numéro 1 au classement de son lycée. En apparence fragile et calme, il dissimule ses véritables compétences. Haïssant particulièrement le harcèlement, il va utiliser son intelligence et sa force pour lutter contre la violence présente à l\'intérieur et à l\'extérieur de son école.', 5, 'Action Drama thriller', 2022, '', 2, 16),
(11, 6, 'Avatar', 'film', 'https://fr.web.img2.acsta.net/c_310_420/pictures/22/08/25/09/04/2146702.jpg', 'Malgré sa paralysie, Jake Sully, un ancien marine immobilisé dans un fauteuil roulant, est resté un combattant au plus profond de son être. Il est recruté pour se rendre à des années-lumière de la Terre, sur Pandora, où de puissants groupes industriels exploitent un minerai rarissime destiné à résoudre la crise énergétique sur Terre. Parce que l\'atmosphère de Pandora est toxique pour les humains, ceux-ci ont créé le Programme Avatar, qui permet à des \" pilotes \" humains de lier leur esprit à un avatar, un corps biologique commandé à distance, capable de survivre dans cette atmosphère létale. Ces avatars sont des hybrides créés génétiquement en croisant l\'ADN humain avec celui des Na\'vi, les autochtones de Pandora.\r\n\r\nSous sa forme d\'avatar, Jake peut de nouveau marcher. On lui confie une mission d\'infiltration auprès des Na\'vi, devenus un obstacle trop conséquent à l\'exploitation du précieux minerai. Mais tout va changer lorsque Neytiri, une très belle Na\'vi, sauve la vie de Jake...\r\n\r\n', 5, 'Sci-Fi', 2009, '2h42', 0, 0),
(12, 6, 'Mercredi', 'film', 'https://media.senscritique.com/media/000020926058/0/mercredi.jpg', 'A présent étudiante à la singulière Nevermore Academy, un pensionnat prestigieux pour parias, Mercredi Addams tente de s\'adapter auprès des autres élèves tout en enquêtant sur une série de meurtres qui terrorise la ville.', 4, 'Comédie,horrifique,fantastique', 2022, '', 2, 16),
(13, 6, 'You', 'série', 'https://m.media-amazon.com/images/M/MV5BMWMyNjM4NTMtMDVjNC00MGZjLWExMjQtMTkzOTc2NTBmZmU4XkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg', 'Le gestionnaire intelligent d\'une librairie compte sur ses connaissances informatique pour que la femme de ses rêves tombe amoureuse de lui. Le gestionnaire intelligent d\'une librairie compte sur ses connaissances informatique pour que la femme de ses rêves tombe amoureuse de lui.', 5, 'Thriller,Psychologique', 2018, '', 0, 0),
(14, 6, 'Wicked', 'film', 'https://fr.web.img4.acsta.net/img/1c/63/1c637da2ba5462df0d49da335124208a.jpg', 'Elphaba, une jeune femme incomprise à cause de la couleur inhabituelle de sa peau verte ne soupçonne même pas l\'étendue de ses pouvoirs. À ses côtés, Glinda qui, aussi populaire que privilégiée, ne connaît pas encore la vraie nature de son cœur. Leur rencontre à l\'Université de Shiz, dans le fantastique monde d\'Oz, marque le début d\'une amitié improbable, mais profonde. Cependant, leur rapport avec Le Magicien d\'Oz va mettre à mal cette amitié et voir leurs chemins s\'éloigner.', 3, 'Musical,Fantaisie', 2024, '2h40', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `bio` text NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'membre',
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `pseudo`, `bio`, `role`, `email`, `mot_de_passe`) VALUES
(1, 'Shelly', 'Membre passionnée de cinéma', 'admin', 'shelly@recomedia.com', '$2y$10$r5m2cUmNKV3kxChVmsnK/uyEhPehw0rjAN33eMdHyYxR4O6.lI8j2'),
(3, 'Emy', 'Romance Lover 🥰', 'membre', 'emy@recomedia.com', '$2y$10$sGI4owFVmyXumFUbTJ6q1.7Bjka448UgQEigus0VV/uy9CKWOOMGq'),
(4, 'Timmy', '', 'membre', 'timmy@recomedia.com', '$2y$10$OVKXbByVcHeZWCW99J51u.yOjIcjAOOnLbMf1Jh1LvcaPWT/zjhyC'),
(5, 'JM', '', 'membre', 'jm@recomedia.com', '$2y$10$bc/3EjRUFm78aB0QI5ccPuyoYlhMBQ9yCFMOYr7AzYCO0J1xSWlnq'),
(6, 'Steffy', 'Membre passionnée de cinéma', 'membre', 'steffy@recomedia.com', '$2y$10$lcqWPM3mCo5WKwMrdrcTl.XsA4MtH3KifLKtoZ7qMrIqm5vGf/YPe'),
(7, 'Eden', 'Membre passionnée de cinéma', 'membre', 'eden@recomedia.com', '$2y$10$8tmMDDYoLig7hUv2LrTA.OgqrWV89wLvSWXZSrVXjjWMwb1d8j2uC');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `contenus`
--
ALTER TABLE `contenus`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commentaires`
--
ALTER TABLE `commentaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `contenus`
--
ALTER TABLE `contenus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
