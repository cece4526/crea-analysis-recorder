-- Tables minimales pour tester les routes modernisées
USE crea_analysis_recorder_test;

SET FOREIGN_KEY_CHECKS = 0;

-- Table production
CREATE TABLE production (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) DEFAULT NULL,
    quantity INT DEFAULT NULL,
    status VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table of (avec nom correct pour éviter les conflits)
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

SET FOREIGN_KEY_CHECKS = 1;
