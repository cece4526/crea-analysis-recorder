-- Migration pour ajouter les champs initialProduction et initialNEP à la table HACCP
-- Date: 7 octobre 2025

USE crea_analysis_recorder;

-- Ajouter les nouvelles colonnes
ALTER TABLE haccp 
ADD COLUMN initialProduction VARCHAR(255) NULL,
ADD COLUMN initialNEP VARCHAR(255) NULL;

-- Vérification de la structure
DESCRIBE haccp;
