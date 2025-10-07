-- Migration pour adapter la table HACCP à l'entité Symfony

-- Création de la nouvelle table selon l'entité Symfony
CREATE TABLE haccp_new (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filtre_pasteurisateur_resultat BOOLEAN NULL,
    temperature_cible INT NULL,
    temperature_indique INT NULL,
    filtre_nep_resultat BOOLEAN NULL,
    of_id INT NULL,
    INDEX idx_of_id (of_id)
);

-- Renommer l'ancienne table et mettre la nouvelle en place
RENAME TABLE haccp TO haccp_old, haccp_new TO haccp;
