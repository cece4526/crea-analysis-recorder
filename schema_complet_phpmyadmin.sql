-- SCHÉMA COMPLET À COPIER DANS PHPMYADMIN
-- Copiez ce contenu dans l'onglet SQL de phpMyAdmin

-- Suppression et création de la base
DROP DATABASE IF EXISTS `crea_analysis_recorder`;
CREATE DATABASE `crea_analysis_recorder` 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

USE `crea_analysis_recorder`;

-- TABLE: production
CREATE TABLE `production` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `date` DATE DEFAULT NULL,
    `quantite` DECIMAL(10,2) DEFAULT NULL,
    `type` VARCHAR(100) DEFAULT NULL,
    `statut` VARCHAR(50) DEFAULT 'en_attente',
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: of (Ordre de Fabrication)
CREATE TABLE `of` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `numero` INT UNIQUE NOT NULL,
    `date` DATE DEFAULT NULL,
    `produit` VARCHAR(255) DEFAULT NULL,
    `quantite` DECIMAL(10,2) DEFAULT NULL,
    `statut` VARCHAR(50) DEFAULT 'en_attente',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: enzyme
CREATE TABLE `enzyme` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(255) NOT NULL,
    `type` VARCHAR(100) DEFAULT NULL,
    `concentration` DECIMAL(8,2) DEFAULT NULL,
    `unite` VARCHAR(50) DEFAULT 'g/L',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: heure_enzyme
CREATE TABLE `heure_enzyme` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `heure` TIME NOT NULL,
    `temperature` DECIMAL(5,2) DEFAULT NULL,
    `ph` DECIMAL(3,1) DEFAULT NULL,
    `vitesse` INT DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: quantite_enzyme
CREATE TABLE `quantite_enzyme` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `quantite` DECIMAL(10,3) NOT NULL,
    `unite` VARCHAR(50) DEFAULT 'mL',
    `date_ajout` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `of_id` INT DEFAULT NULL,
    `enzyme_id` INT DEFAULT NULL,
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`enzyme_id`) REFERENCES `enzyme`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: analyse_soja
CREATE TABLE `analyse_soja` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `date_analyse` DATE DEFAULT NULL,
    `taux_proteine` DECIMAL(5,2) DEFAULT NULL,
    `taux_humidite` DECIMAL(5,2) DEFAULT NULL,
    `ph` DECIMAL(3,1) DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: ap_correct_cereales
CREATE TABLE `ap_correct_cereales` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `date_correction` DATE DEFAULT NULL,
    `type_correction` VARCHAR(255) DEFAULT NULL,
    `valeur_avant` DECIMAL(10,2) DEFAULT NULL,
    `valeur_apres` DECIMAL(10,2) DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: ap_correct_soja
CREATE TABLE `ap_correct_soja` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `date_correction` DATE DEFAULT NULL,
    `type_correction` VARCHAR(255) DEFAULT NULL,
    `valeur_avant` DECIMAL(10,2) DEFAULT NULL,
    `valeur_apres` DECIMAL(10,2) DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: av_correct_cereales  
CREATE TABLE `av_correct_cereales` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `date_mesure` DATE DEFAULT NULL,
    `valeur_mesuree` DECIMAL(10,2) DEFAULT NULL,
    `unite` VARCHAR(50) DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: av_correct_soja
CREATE TABLE `av_correct_soja` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `date_mesure` DATE DEFAULT NULL,
    `valeur_mesuree` DECIMAL(10,2) DEFAULT NULL,
    `unite` VARCHAR(50) DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: cuve_cereales
CREATE TABLE `cuve_cereales` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `numero_cuve` VARCHAR(50) NOT NULL,
    `capacite` DECIMAL(10,2) DEFAULT NULL,
    `temperature` DECIMAL(5,2) DEFAULT NULL,
    `niveau_remplissage` DECIMAL(5,2) DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: decanteur_cereales
CREATE TABLE `decanteur_cereales` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `numero_decanteur` VARCHAR(50) NOT NULL,
    `vitesse_rotation` INT DEFAULT NULL,
    `temperature` DECIMAL(5,2) DEFAULT NULL,
    `duree_cycle` INT DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: echantillons
CREATE TABLE `echantillons` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `numero_echantillon` VARCHAR(100) NOT NULL,
    `date_prelevement` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `type_echantillon` VARCHAR(100) DEFAULT NULL,
    `volume` DECIMAL(8,2) DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: haccp
CREATE TABLE `haccp` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `point_critique` VARCHAR(255) NOT NULL,
    `limite_critique` VARCHAR(255) DEFAULT NULL,
    `mesure_actuelle` DECIMAL(10,2) DEFAULT NULL,
    `conforme` BOOLEAN DEFAULT TRUE,
    `date_controle` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: okara
CREATE TABLE `okara` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `quantite_produite` DECIMAL(10,2) DEFAULT NULL,
    `taux_humidite` DECIMAL(5,2) DEFAULT NULL,
    `destination` VARCHAR(255) DEFAULT NULL,
    `date_production` DATE DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ajout de la clé étrangère pour production
ALTER TABLE `production` ADD FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE SET NULL;

-- Insertion de données de test
INSERT INTO `of` (`numero`, `date`, `produit`, `quantite`, `statut`) VALUES
(76931, '2025-07-25', 'Céréales Bio', 1500.00, 'en_cours'),
(76932, '2025-07-25', 'Céréales Standard', 2000.00, 'en_cours');

INSERT INTO `production` (`date`, `quantite`, `type`, `statut`, `of_id`) VALUES
('2025-07-25', 1500.00, 'cereales', 'en_cours', 1),
('2025-07-25', 2000.00, 'cereales', 'en_cours', 2);

INSERT INTO `enzyme` (`nom`, `type`, `concentration`) VALUES
('Amylase Alpha', 'Hydrolytique', 15.5),
('Cellulase', 'Hydrolytique', 12.0);

-- Index pour optimisation
CREATE INDEX idx_of_statut ON `of`(`statut`);
CREATE INDEX idx_production_statut ON `production`(`statut`);
CREATE INDEX idx_haccp_conforme ON `haccp`(`conforme`);

-- Vérification des tables créées
SHOW TABLES;
