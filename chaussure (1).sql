-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 21 déc. 2021 à 10:26
-- Version du serveur : 10.4.21-MariaDB
-- Version de PHP : 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `shoesme`
--

-- --------------------------------------------------------

--
-- Structure de la table `chaussure`
--

CREATE TABLE `chaussure` (
  `id` int(11) NOT NULL,
  `marque` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `matiere` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptif` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` double NOT NULL,
  `sexe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `chaussure`
--

INSERT INTO `chaussure` (`id`, `marque`, `model`, `type`, `matiere`, `descriptif`, `photo`, `prix`, `sexe`) VALUES
(1, 'Nike', 'AirMax Plus', 'Sneakers', 'Textile', 'Courez à travers les flaques d\'eau grâce à l\'amorti testé et approuvé de la Nike Air Max Plus.Elle conserve les empiècements dégradés emblématiques sur les côtés, en tissu CORDURA® imperméable pour ne pas être gêné par la pluie', 'nike1-61c0858d03113.jpg', 179.99, 'm'),
(2, 'Nike', 'AirMax 90', 'Sneakers', 'Textile', 'Cette chaussure classique va avec tout et s\'adapte à toutes les circonstances. Dotée de la semelle emblématique et de l\'unité Max Air originale, elle a été mise au goût du jour avec des couleurs élégantes parfaites pour la piste ou le quotidien.', 'nike2-61c0866b99daa.jpg', 139.99, 'm'),
(3, 'Nike', 'AirMax Plus 3', 'Sneakers', 'Textile', 'Équipée de la même technologie Tuned Air que ses aînées, la Nike Air Max Plus III revisite un style classique avec des éléments en TPU fusionnés sur l\'empeigne, tout en rendant hommage à l\'emblématique dégradé de couleurs de l\'originale.', 'nike3-61c08731287e1.jpg', 179.99, 'm'),
(4, 'Lacoste', 'T-Clip', 'Sneakers', 'Cuir et textile', 'Inspiré par les sneakers Sideline, le modèle T-Clip présente une coupe inspirée de l’univers du tennis rehaussé d’éléments de design provenant des années 80 et de détails modernes et pratiques. La tige déperlante est composée de cuir souple présentant des empiècements en maille et en nubuck et la doublure est renforcée par la technologie d’isolation PrimaLoft qui garde les pieds au chaud. Proposé dans des couleurs neutres avec des détails de couleurs tels que les lacets jaspés, les bandes obliques sur les côtés et le crocodile en caoutchouc, ce modèle présente une esthétique minimaliste mais texturée.', 'lacoste1-61c0879b79d14.jpg', 110, 'm'),
(5, 'Lacoste', 'T-Clip Lacoste x Peanuts', 'Sneakers', 'Cuir et textile', 'En collaboration avec Peanuts et son incontournable bande dessinée, ce nouveau modèle des sneakers T-Clip a été réinventé avec un spectre de couleurs vives et un Snoopy prêt à jouer sur le talon. Une paire de sneakers luxueuse et durable, dont la tige conçue en cuir et en daim repose sur une semelle cupsole en caoutchouc à l’adhérence optimisée. Des perforations viennent décorer le quartier et le bout. Notez la version ludique du crocodile vert brodé apparaissant sur le quartier, avec une balle de tennis entre ses crocs.', 'lacoste2-61c087deb3feb.jpg', 125, 'm'),
(6, 'Lacoste', 'L-Guard Breaker', 'Sneakers', 'Daim et textile', 'Préparez-vous pour les aventures en extérieur avec le modèle L-Guard Breaker. Ces chaussures multifonctionnelles sont composées de textile et de daim. Son design préparé pour l’hiver s’adapte à tous les temps et à tous les terrains. La semelle extérieure en caoutchouc Goodyear présente un talon élevé pour leur assurer plus de durabilité et de stabilité, tandis que les amples crampons en caoutchouc fournissent une meilleure prise et une traction améliorée. À l’intérieur, la semelle interne en ortholite offre plus de confort et de soutien au pied. Le modèle est complété par le crocodile figurant sur le quartier, en guise de signature de la marque.', 'lacoste3-61c088dc70e80.png', 139.99, 'm'),
(7, 'Adidas', 'ZX 2K BOOST UTILITY GORE-TEX', 'Sneakers', 'Textile', 'Parfaite pour explorer la ville, cette chaussure adidas t\'offre toute la liberté dont tu as besoin. La tige GORE-TEX te protège du froid et de la pluie, et sa semelle extérieure robuste offre une accroche optimale sur les surfaces accidentées. L\'amorti Boost ajoute un confort ultime à l\'équation. Si tu te retrouves hors des sentiers battus, tant mieux.', 'adidas1-61c08983c6246.jpg', 180, 'm'),
(8, 'Adidas', 'OZWEEGO', 'Sneakers', 'Tissu', 'L\'approche anti-conformiste de la mode des années 90 a marqué l\'époque. Les règles ? Bouscule-les. Twiste-les. Crée le changement. La chaussure adidas OZWEEGO l\'a fait à l\'époque, et cette version moderne révèle la même énergie. Les lignes audacieuses de la semelle intermédiaire rendent hommage aux icônes. La tige premium conçue dans un mix de matières est associée à un amorti qui t\'offre du confort tout au long de la journée.', 'adidas2-61c089afc98f1.jpg', 120, 'm'),
(9, 'Adidas', 'Y-3 HOKORI II', 'Sneakers', 'Textile', 'Inspirée d\'une des chaussures de basketball les plus en vue des années 80, la Y-3 Hokori est remise au goût du jour de manière sophistiquée. Elle est conçue dans un mix de suède premium et de néoprène qui s\'assemblent pour créer une silhouette basse audacieuse, mise en valeur par l\'épaisse semelle extérieure.', 'adidas3-61c08a12cc763.jpg', 280, 'm'),
(11, 'Nike', 'Giannis Immortality', 'Sneakers', 'Textile', 'L\'avenir du basketball n\'a pas de positions définies et personne n\'incarne cette évolution aussi bien que le MVP Giannis Antetokounmpo. La chaussure de match Giannis Immortality s\'adresse aux joueurs qui apprécient le jeu multidimensionnel de Giannis et veulent adopter son style décalé.', 'Nike2femme-61c19412c55cb.jpg', 79.99, 'f'),
(12, 'Nike', 'Air Force 1 Shadow', 'Sneakers', 'Textile', 'La Nike Air Force 1 Shadow apporte une touche ludique à un modèle classique du basketball.L\'ADN de la AF1 est mis en valeur par l\'esthétique superposée, le double logo et la semelle intermédiaire oversize de ce nouveau modèle audacieux.', '1-61c194f1953f8.webp', 119.99, 'f'),
(14, 'Nike', 'Metcon 7', 'Sneakers', 'Textile', 'La Nike Metcon 7 est une référence dans l\'univers de la musculation, car elle est encore plus résistante et stable que les versions précédentes. Nous avons ajouté de la mousse React pour vous offrir encore plus de confort lors de vos entraînements cardio de haute intensité. De plus, une languette bloque vos lacets pour éviter toute gêne et vous permettre de rester concentré sur votre prochain record personnel.', '1-61c1958f8c154.webp', 129.99, 'f'),
(15, 'Adidas', 'Ozelia', 'Sneakers', 'Textile', 'Les années 90 ont été synonymes de styles, et cette chaussure adidas Ozelia s\'inspire des meilleurs d\'entre eux. Nous avons également ajouté des éléments de confort modernes, comme l\'amorti Adiprene doux et un chaussant ajusté qui épouse les contours du pied.', '1-61c197a7db1e8.webp', 110, 'f'),
(16, 'Adidas', 'Superstar OT Tech', 'Sneakers', 'Textile', 'Il n\'en faut pas beaucoup pour sublimer une tenue. Peut-être un accessoire ou deux ? Cette chaussure adidas Superstar s\'occupe du côté bling. Des détails dorés métallisés rehaussent la silhouette iconique, mettant en valeur les 3 bandes dentelées emblématiques. Maintenant, tu vas faire sensation.', '1-61c19889aad1c.webp', 110, 'f'),
(17, 'Adidas', 'ZX 5K BOOST', 'Sneakers', 'Textile', 'Prépare-toi pour le futur. Cette chaussure adidas ZX 5K BOOST t\'accompagne à chaque pas. Incorporant le meilleur de la technologie moderne à la gamme ZX en constante évolution, elle t\'apporte style et énergie. L\'amorti BOOST t\'assure un confort ultime. Les détails translucides et réfléchissants te donnent un look unique.', '1-61c1990d68e2b.webp', 160, 'f'),
(18, 'Lacoste', 'L001', 'Sneakers', 'Cuir', 'Le modèle L001 de Lacoste est un classique instantané. Complètement nouveau, il prouve que la synergie de la réinvention et l’attrait nostalgique figurent toujours au cœur de la marque. Le profil inspiré de l’univers du tennis est rehaussé par une tige en cuir foulé souple et des touches en daim de luxe, tandis que les touches de couleur lui donnent un aspect dynamique. La semelle extérieure propose des détails géométriques étonnants, et notamment un empiècement triangulaire inspiré par les raquettes de tennis vintage des années 80. Le modèle est complété par des logos sur le talon et la languette.', '1-61c19a0ba758c.webp', 110, 'f'),
(19, 'Lacoste', 'Jump Serve Lacoste x Peanuts', 'Sneakers', 'Textile', 'Avec son Snoopy sagement situé sur le côté de la tige en toile, cette version du modèle Jump Serve à lacets, créée en collaboration avec Peanuts, est un véritable appel aux sneakers décontractées. Créées pour un effet chaussures bateau vulcanisées, leur style élégant est naturellement simple, et se compose de polyester recyclé pour une touche de durabilité. Une citation inspirante de René Lacoste est présente sur la languette tandis que le crocodile vert brodé apparaît sur le talon.', '1-61c19b0698b15.webp', 110, 'f'),
(20, 'Lacoste', 'Storm 96 Lo', 'Sneakers', 'Textile', 'Les Storm 96, inspirées des anciens modèles, représentent une version revisitée des premières chaussures de running Lacoste. La tige en cuir et en matière synthétique est rehaussée par des empiècements en daim sur les œillets, le talon et le bout tandis que les touches de couleurs vives offrent un style qui attire le regard. Le modèle présente une doublure en polyester recyclé qui reflète l’engagement de Lacoste envers la durabilité de ses produits. Le crocodile vert brodé sur le quartier et le marquage en caoutchouc sur le talon apportent une décoration signature.', '1-61c19b8c96ee7.webp', 120, 'f');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `chaussure`
--
ALTER TABLE `chaussure`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `chaussure`
--
ALTER TABLE `chaussure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
