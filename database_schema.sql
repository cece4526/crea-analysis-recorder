-- =====================================================
-- Script SQL pour la base de données crea-analysis-recorder
-- Base de données MySQL pour le projet Symfony
-- Date: 7 octobre 2025
-- =====================================================

-- Suppression de la base si elle existe et création
DROP DATABASE IF EXISTS `crea_analysis_recorder`;
CREATE DATABASE `crea_analysis_recorder` 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

USE `crea_analysis_recorder`;

-- =====================================================
-- TABLE: production
-- =====================================================
CREATE TABLE `production` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) DEFAULT NULL,
    `quantity` INT DEFAULT NULL,
    `status` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: of (Ordre de Fabrication)
-- =====================================================
CREATE TABLE `of` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `numero` VARCHAR(50) UNIQUE NOT NULL,
    `date_creation` DATE DEFAULT NULL,
    `date_prevue` DATE DEFAULT NULL,
    `date_realisation` DATE DEFAULT NULL,
    `statut` VARCHAR(100) DEFAULT 'En attente',
    `quantite_prevue` INT DEFAULT NULL,
    `quantite_realisee` INT DEFAULT NULL,
    `commentaires` TEXT DEFAULT NULL,
    `production_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`production_id`) REFERENCES `production`(`id`) ON DELETE SET NULL,
    INDEX `idx_of_production` (`production_id`),
    INDEX `idx_of_numero` (`numero`),
    INDEX `idx_of_statut` (`statut`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: enzyme
-- =====================================================
CREATE TABLE `enzyme` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(255) NOT NULL,
    `type` VARCHAR(100) DEFAULT NULL,
    `concentration` DECIMAL(8,2) DEFAULT NULL,
    `unite` VARCHAR(50) DEFAULT 'g/L',
    `fournisseur` VARCHAR(255) DEFAULT NULL,
    `date_peremption` DATE DEFAULT NULL,
    `temperature_stockage` DECIMAL(5,2) DEFAULT NULL,
    `ph_optimal` DECIMAL(3,1) DEFAULT NULL,
    `actif` BOOLEAN DEFAULT TRUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX `idx_enzyme_nom` (`nom`),
    INDEX `idx_enzyme_type` (`type`),
    INDEX `idx_enzyme_actif` (`actif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: quantite_enzyme
-- =====================================================
CREATE TABLE `quantite_enzyme` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `quantite` DECIMAL(10,3) NOT NULL,
    `unite` VARCHAR(50) DEFAULT 'mL',
    `date_ajout` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `temperature` DECIMAL(5,2) DEFAULT NULL,
    `ph` DECIMAL(3,1) DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `enzyme_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`enzyme_id`) REFERENCES `enzyme`(`id`) ON DELETE CASCADE,
    INDEX `idx_quantite_enzyme_of` (`of_id`),
    INDEX `idx_quantite_enzyme_enzyme` (`enzyme_id`),
    INDEX `idx_quantite_enzyme_date` (`date_ajout`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: heure_enzyme
-- =====================================================
CREATE TABLE `heure_enzyme` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `heure_ajout` TIME NOT NULL,
    `date_ajout` DATE DEFAULT NULL,
    `timestamp_complet` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `operateur` VARCHAR(255) DEFAULT NULL,
    `commentaire` TEXT DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE,
    INDEX `idx_heure_enzyme_of` (`of_id`),
    INDEX `idx_heure_enzyme_date` (`date_ajout`),
    INDEX `idx_heure_enzyme_heure` (`heure_ajout`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: analyse_soja
-- =====================================================
CREATE TABLE `analyse_soja` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `date_analyse` DATE NOT NULL,
    `heure_analyse` TIME DEFAULT NULL,
    `taux_proteine` DECIMAL(5,2) DEFAULT NULL,
    `taux_humidite` DECIMAL(5,2) DEFAULT NULL,
    `ph` DECIMAL(3,1) DEFAULT NULL,
    `temperature` DECIMAL(5,2) DEFAULT NULL,
    `densite` DECIMAL(6,3) DEFAULT NULL,
    `couleur` VARCHAR(100) DEFAULT NULL,
    `texture` VARCHAR(100) DEFAULT NULL,
    `gout` VARCHAR(100) DEFAULT NULL,
    `conformite` BOOLEAN DEFAULT TRUE,
    `commentaires` TEXT DEFAULT NULL,
    `analyste` VARCHAR(255) DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE,
    INDEX `idx_analyse_soja_of` (`of_id`),
    INDEX `idx_analyse_soja_date` (`date_analyse`),
    INDEX `idx_analyse_soja_conformite` (`conformite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: cuve_cereales
-- =====================================================
CREATE TABLE `cuve_cereales` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `numero_cuve` VARCHAR(50) NOT NULL,
    `type_cereale` VARCHAR(100) NOT NULL,
    `volume` DECIMAL(10,2) DEFAULT NULL,
    `temperature` DECIMAL(5,2) DEFAULT NULL,
    `pression` DECIMAL(6,2) DEFAULT NULL,
    `date_remplissage` DATETIME DEFAULT NULL,
    `date_vidange` DATETIME DEFAULT NULL,
    `statut` VARCHAR(100) DEFAULT 'Vide',
    `operateur` VARCHAR(255) DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE SET NULL,
    INDEX `idx_cuve_cereales_of` (`of_id`),
    INDEX `idx_cuve_cereales_numero` (`numero_cuve`),
    INDEX `idx_cuve_cereales_type` (`type_cereale`),
    INDEX `idx_cuve_cereales_statut` (`statut`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: decanteur_cereales
-- =====================================================
CREATE TABLE `decanteur_cereales` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `numero_decanteur` VARCHAR(50) NOT NULL,
    `type_cereale` VARCHAR(100) NOT NULL,
    `volume_initial` DECIMAL(10,2) DEFAULT NULL,
    `volume_final` DECIMAL(10,2) DEFAULT NULL,
    `temps_decantation` INT DEFAULT NULL COMMENT 'Temps en minutes',
    `temperature` DECIMAL(5,2) DEFAULT NULL,
    `taux_separation` DECIMAL(5,2) DEFAULT NULL,
    `date_debut` DATETIME DEFAULT NULL,
    `date_fin` DATETIME DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE,
    INDEX `idx_decanteur_cereales_of` (`of_id`),
    INDEX `idx_decanteur_cereales_numero` (`numero_decanteur`),
    INDEX `idx_decanteur_cereales_type` (`type_cereale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: echantillons
-- =====================================================
CREATE TABLE `echantillons` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `numero_echantillon` VARCHAR(100) UNIQUE NOT NULL,
    `type_echantillon` VARCHAR(100) NOT NULL,
    `date_prelevement` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `lieu_prelevement` VARCHAR(255) DEFAULT NULL,
    `volume` DECIMAL(8,2) DEFAULT NULL,
    `unite_volume` VARCHAR(20) DEFAULT 'mL',
    `temperature_prelevement` DECIMAL(5,2) DEFAULT NULL,
    `conditions_stockage` VARCHAR(255) DEFAULT NULL,
    `date_analyse_prevue` DATE DEFAULT NULL,
    `statut` VARCHAR(100) DEFAULT 'Prélevé',
    `operateur` VARCHAR(255) DEFAULT NULL,
    `commentaires` TEXT DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE,
    INDEX `idx_echantillons_of` (`of_id`),
    INDEX `idx_echantillons_numero` (`numero_echantillon`),
    INDEX `idx_echantillons_type` (`type_echantillon`),
    INDEX `idx_echantillons_statut` (`statut`),
    INDEX `idx_echantillons_date_prelevement` (`date_prelevement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: haccp
-- =====================================================
CREATE TABLE `haccp` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `point_controle` VARCHAR(255) NOT NULL,
    `type_danger` ENUM('Biologique', 'Chimique', 'Physique') NOT NULL,
    `limite_critique` VARCHAR(255) NOT NULL,
    `methode_surveillance` TEXT DEFAULT NULL,
    `frequence_controle` VARCHAR(100) DEFAULT NULL,
    `responsable` VARCHAR(255) DEFAULT NULL,
    `actions_correctives` TEXT DEFAULT NULL,
    `date_controle` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `valeur_mesuree` VARCHAR(255) DEFAULT NULL,
    `conforme` BOOLEAN DEFAULT TRUE,
    `observations` TEXT DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE,
    INDEX `idx_haccp_of` (`of_id`),
    INDEX `idx_haccp_point_controle` (`point_controle`),
    INDEX `idx_haccp_type_danger` (`type_danger`),
    INDEX `idx_haccp_conforme` (`conforme`),
    INDEX `idx_haccp_date_controle` (`date_controle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: okara
-- =====================================================
CREATE TABLE `okara` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `date_production` DATE NOT NULL,
    `quantite` DECIMAL(10,2) NOT NULL,
    `unite` VARCHAR(20) DEFAULT 'kg',
    `taux_humidite` DECIMAL(5,2) DEFAULT NULL,
    `temperature` DECIMAL(5,2) DEFAULT NULL,
    `couleur` VARCHAR(100) DEFAULT NULL,
    `texture` VARCHAR(100) DEFAULT NULL,
    `destination` VARCHAR(255) DEFAULT NULL COMMENT 'Compost, alimentation animale, etc.',
    `date_evacuation` DATE DEFAULT NULL,
    `responsable` VARCHAR(255) DEFAULT NULL,
    `commentaires` TEXT DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE,
    INDEX `idx_okara_of` (`of_id`),
    INDEX `idx_okara_date_production` (`date_production`),
    INDEX `idx_okara_destination` (`destination`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: ap_correct_cereales (Avant Production - Correction Céréales)
-- =====================================================
CREATE TABLE `ap_correct_cereales` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `type_cereale` VARCHAR(100) NOT NULL,
    `parametre_corrige` VARCHAR(255) NOT NULL,
    `valeur_initiale` DECIMAL(10,3) DEFAULT NULL,
    `valeur_cible` DECIMAL(10,3) DEFAULT NULL,
    `valeur_finale` DECIMAL(10,3) DEFAULT NULL,
    `methode_correction` VARCHAR(255) DEFAULT NULL,
    `date_correction` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `operateur` VARCHAR(255) DEFAULT NULL,
    `duree_correction` INT DEFAULT NULL COMMENT 'Durée en minutes',
    `commentaires` TEXT DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE,
    INDEX `idx_ap_correct_cereales_of` (`of_id`),
    INDEX `idx_ap_correct_cereales_type` (`type_cereale`),
    INDEX `idx_ap_correct_cereales_parametre` (`parametre_corrige`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: av_correct_cereales (Après Production - Correction Céréales)
-- =====================================================
CREATE TABLE `av_correct_cereales` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `type_cereale` VARCHAR(100) NOT NULL,
    `parametre_corrige` VARCHAR(255) NOT NULL,
    `valeur_mesuree` DECIMAL(10,3) DEFAULT NULL,
    `valeur_attendue` DECIMAL(10,3) DEFAULT NULL,
    `ecart` DECIMAL(10,3) DEFAULT NULL,
    `action_corrective` VARCHAR(255) DEFAULT NULL,
    `date_mesure` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `operateur` VARCHAR(255) DEFAULT NULL,
    `validation` BOOLEAN DEFAULT FALSE,
    `commentaires` TEXT DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE,
    INDEX `idx_av_correct_cereales_of` (`of_id`),
    INDEX `idx_av_correct_cereales_type` (`type_cereale`),
    INDEX `idx_av_correct_cereales_validation` (`validation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: ap_correct_soja (Avant Production - Correction Soja)
-- =====================================================
CREATE TABLE `ap_correct_soja` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `parametre_corrige` VARCHAR(255) NOT NULL,
    `valeur_initiale` DECIMAL(10,3) DEFAULT NULL,
    `valeur_cible` DECIMAL(10,3) DEFAULT NULL,
    `valeur_finale` DECIMAL(10,3) DEFAULT NULL,
    `methode_correction` VARCHAR(255) DEFAULT NULL,
    `date_correction` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `operateur` VARCHAR(255) DEFAULT NULL,
    `duree_correction` INT DEFAULT NULL COMMENT 'Durée en minutes',
    `temperature_correction` DECIMAL(5,2) DEFAULT NULL,
    `commentaires` TEXT DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE,
    INDEX `idx_ap_correct_soja_of` (`of_id`),
    INDEX `idx_ap_correct_soja_parametre` (`parametre_corrige`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: av_correct_soja (Après Production - Correction Soja)
-- =====================================================
CREATE TABLE `av_correct_soja` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `parametre_corrige` VARCHAR(255) NOT NULL,
    `valeur_mesuree` DECIMAL(10,3) DEFAULT NULL,
    `valeur_attendue` DECIMAL(10,3) DEFAULT NULL,
    `ecart` DECIMAL(10,3) DEFAULT NULL,
    `action_corrective` VARCHAR(255) DEFAULT NULL,
    `date_mesure` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `operateur` VARCHAR(255) DEFAULT NULL,
    `validation` BOOLEAN DEFAULT FALSE,
    `temperature_mesure` DECIMAL(5,2) DEFAULT NULL,
    `commentaires` TEXT DEFAULT NULL,
    `of_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`of_id`) REFERENCES `of`(`id`) ON DELETE CASCADE,
    INDEX `idx_av_correct_soja_of` (`of_id`),
    INDEX `idx_av_correct_soja_parametre` (`parametre_corrige`),
    INDEX `idx_av_correct_soja_validation` (`validation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERTION DE DONNÉES DE TEST
-- =====================================================

-- Données de test pour Production
INSERT INTO `production` (`name`, `quantity`, `status`) VALUES
('Production Lait de Soja Bio', 1000, 'En cours'),
('Production Lait de Riz Complet', 750, 'Terminée'),
('Production Lait d''Avoine', 500, 'En attente'),
('Production Mélange Céréales', 1200, 'En cours');

-- Données de test pour Enzyme
INSERT INTO `enzyme` (`nom`, `type`, `concentration`, `unite`, `fournisseur`, `date_peremption`, `temperature_stockage`, `ph_optimal`, `actif`) VALUES
('Amylase Alpha', 'Hydrolyse', 100.00, 'U/mL', 'BioEnzymes Ltd', '2025-12-31', 4.0, 6.5, TRUE),
('Cellulase', 'Dégradation', 75.50, 'U/mL', 'EnzymeTech', '2025-10-15', 2.0, 5.0, TRUE),
('Protéase', 'Protéolyse', 150.00, 'U/mL', 'BioSolutions', '2026-03-20', 4.0, 7.0, TRUE);

-- Données de test pour OF
INSERT INTO `of` (`numero`, `date_creation`, `date_prevue`, `statut`, `quantite_prevue`, `production_id`) VALUES
('OF-2025-001', '2025-10-01', '2025-10-15', 'En cours', 1000, 1),
('OF-2025-002', '2025-10-02', '2025-10-10', 'Terminé', 750, 2),
('OF-2025-003', '2025-10-03', '2025-10-20', 'En attente', 500, 3);

-- =====================================================
-- VUES UTILES POUR L'ANALYSE
-- =====================================================

-- Vue pour le suivi de production
CREATE VIEW `vue_suivi_production` AS
SELECT 
    p.id as production_id,
    p.name as production_name,
    p.status as production_status,
    COUNT(o.id) as nombre_of,
    SUM(o.quantite_prevue) as quantite_totale_prevue,
    SUM(o.quantite_realisee) as quantite_totale_realisee,
    AVG(CASE WHEN o.quantite_prevue > 0 THEN (o.quantite_realisee / o.quantite_prevue) * 100 END) as taux_realisation
FROM production p
LEFT JOIN of o ON p.id = o.production_id
GROUP BY p.id, p.name, p.status;

-- Vue pour les analyses HACCP
CREATE VIEW `vue_haccp_conformite` AS
SELECT 
    h.point_controle,
    h.type_danger,
    COUNT(*) as total_controles,
    SUM(CASE WHEN h.conforme = TRUE THEN 1 ELSE 0 END) as controles_conformes,
    (SUM(CASE WHEN h.conforme = TRUE THEN 1 ELSE 0 END) / COUNT(*)) * 100 as taux_conformite
FROM haccp h
WHERE h.date_controle >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY h.point_controle, h.type_danger;

-- Vue pour les performances par OF
CREATE VIEW `vue_performance_of` AS
SELECT 
    o.numero,
    o.statut,
    p.name as production_name,
    o.quantite_prevue,
    o.quantite_realisee,
    CASE 
        WHEN o.quantite_prevue > 0 THEN (o.quantite_realisee / o.quantite_prevue) * 100 
        ELSE 0 
    END as taux_realisation,
    DATEDIFF(o.date_realisation, o.date_prevue) as retard_jours,
    COUNT(qe.id) as nombre_ajouts_enzyme
FROM of o
LEFT JOIN production p ON o.production_id = p.id
LEFT JOIN quantite_enzyme qe ON o.id = qe.of_id
GROUP BY o.id;

-- =====================================================
-- PROCÉDURES STOCKÉES UTILES
-- =====================================================

DELIMITER //

-- Procédure pour calculer les statistiques de production
CREATE PROCEDURE `CalculerStatistiquesProduction`(IN production_id INT)
BEGIN
    SELECT 
        p.name,
        COUNT(o.id) as nombre_of,
        SUM(o.quantite_prevue) as quantite_prevue_totale,
        SUM(o.quantite_realisee) as quantite_realisee_totale,
        AVG(CASE WHEN o.quantite_prevue > 0 THEN (o.quantite_realisee / o.quantite_prevue) * 100 END) as taux_realisation_moyen,
        COUNT(CASE WHEN o.statut = 'Terminé' THEN 1 END) as of_termines,
        COUNT(CASE WHEN o.statut = 'En cours' THEN 1 END) as of_en_cours
    FROM production p
    LEFT JOIN of o ON p.id = o.production_id
    WHERE p.id = production_id
    GROUP BY p.id, p.name;
END //

-- Procédure pour obtenir le rapport HACCP
CREATE PROCEDURE `RapportHACCP`(IN date_debut DATE, IN date_fin DATE)
BEGIN
    SELECT 
        point_controle,
        type_danger,
        COUNT(*) as total_controles,
        SUM(CASE WHEN conforme = TRUE THEN 1 ELSE 0 END) as conformes,
        SUM(CASE WHEN conforme = FALSE THEN 1 ELSE 0 END) as non_conformes,
        (SUM(CASE WHEN conforme = TRUE THEN 1 ELSE 0 END) / COUNT(*)) * 100 as taux_conformite
    FROM haccp 
    WHERE DATE(date_controle) BETWEEN date_debut AND date_fin
    GROUP BY point_controle, type_danger
    ORDER BY taux_conformite ASC;
END //

DELIMITER ;

-- =====================================================
-- INDEX SUPPLÉMENTAIRES POUR LES PERFORMANCES
-- =====================================================

-- Index composites pour les requêtes fréquentes
CREATE INDEX `idx_of_production_statut` ON `of` (`production_id`, `statut`);
CREATE INDEX `idx_quantite_enzyme_of_enzyme` ON `quantite_enzyme` (`of_id`, `enzyme_id`);
CREATE INDEX `idx_haccp_date_conforme` ON `haccp` (`date_controle`, `conforme`);
CREATE INDEX `idx_echantillons_date_statut` ON `echantillons` (`date_prelevement`, `statut`);

-- =====================================================
-- UTILISATEURS ET PERMISSIONS (à adapter selon vos besoins)
-- =====================================================

-- Créer un utilisateur pour l'application
-- CREATE USER 'crea_app'@'localhost' IDENTIFIED BY 'VotreMotDePasseSecurise';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON crea_analysis_recorder.* TO 'crea_app'@'localhost';

-- Créer un utilisateur en lecture seule pour les rapports
-- CREATE USER 'crea_reports'@'localhost' IDENTIFIED BY 'MotDePasseReports';
-- GRANT SELECT ON crea_analysis_recorder.* TO 'crea_reports'@'localhost';

-- FLUSH PRIVILEGES;

-- =====================================================
-- FIN DU SCRIPT
-- =====================================================

SELECT 'Base de données crea_analysis_recorder créée avec succès !' as Message;
