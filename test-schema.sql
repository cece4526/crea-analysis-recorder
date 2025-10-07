-- Script simplifié pour créer les tables de test
USE crea_analysis_recorder_test;

-- Désactiver les checks de foreign key temporairement
SET FOREIGN_KEY_CHECKS = 0;

-- Table production
DROP TABLE IF EXISTS production;
CREATE TABLE production (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) DEFAULT NULL,
    quantity INT DEFAULT NULL,
    status VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table of 
DROP TABLE IF EXISTS `of`;
CREATE TABLE `of` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(50) UNIQUE NOT NULL,
    date_creation DATE DEFAULT NULL,
    date_prevue DATE DEFAULT NULL,
    date_realisation DATE DEFAULT NULL,
    statut VARCHAR(100) DEFAULT 'En attente',
    quantite_prevue INT DEFAULT NULL,
    quantite_realisee INT DEFAULT NULL,
    commentaires TEXT DEFAULT NULL,
    production_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (production_id) REFERENCES production(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table heure_enzyme
DROP TABLE IF EXISTS heure_enzyme;
CREATE TABLE heure_enzyme (
    id INT AUTO_INCREMENT PRIMARY KEY,
    of_id INT DEFAULT NULL,
    heure_debut TIME DEFAULT NULL,
    heure_fin TIME DEFAULT NULL,
    duree_minutes INT DEFAULT NULL,
    observations TEXT DEFAULT NULL,
    type_enzyme VARCHAR(100) DEFAULT NULL,
    quantite_enzyme DECIMAL(10,3) DEFAULT NULL,
    unite_quantite VARCHAR(20) DEFAULT NULL,
    temperature_initiale DECIMAL(5,2) DEFAULT NULL,
    temperature_finale DECIMAL(5,2) DEFAULT NULL,
    temperature_moyenne DECIMAL(5,2) DEFAULT NULL,
    ph_initial DECIMAL(4,2) DEFAULT NULL,
    ph_final DECIMAL(4,2) DEFAULT NULL,
    ph_moyen DECIMAL(4,2) DEFAULT NULL,
    duree_planifiee INT DEFAULT NULL,
    efficacite_calculee DECIMAL(5,2) DEFAULT NULL,
    conformite TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (of_id) REFERENCES `of`(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table analyse_soja
DROP TABLE IF EXISTS analyse_soja;
CREATE TABLE analyse_soja (
    id INT AUTO_INCREMENT PRIMARY KEY,
    of_id INT DEFAULT NULL,
    date_analyse DATE DEFAULT NULL,
    proteine DECIMAL(5,2) DEFAULT NULL,
    humidite DECIMAL(5,2) DEFAULT NULL,
    matiere_grasse DECIMAL(5,2) DEFAULT NULL,
    conforme TINYINT(1) DEFAULT 1,
    observations TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (of_id) REFERENCES `of`(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Réactiver les checks de foreign key
SET FOREIGN_KEY_CHECKS = 1;
