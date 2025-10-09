-- Script pour renommer la table of vers ordre_fabrication
USE crea_analysis_recorder;

-- Créer la nouvelle table ordre_fabrication avec la même structure que of
CREATE TABLE ordre_fabrication LIKE `of`;

-- Copier toutes les données de of vers ordre_fabrication
INSERT INTO ordre_fabrication SELECT * FROM `of`;

-- Mettre à jour les clés étrangères dans les autres tables
UPDATE cuve_cereales SET of_id = of_id WHERE of_id IS NOT NULL;

-- Vérifier que les données ont été copiées correctement
SELECT COUNT(*) as count_of FROM `of`;
SELECT COUNT(*) as count_ordre_fabrication FROM ordre_fabrication;
