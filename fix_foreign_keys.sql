USE crea_analysis_recorder;

-- Supprimer temporairement les contraintes de clé étrangère
ALTER TABLE analyse_soja DROP FOREIGN KEY analyse_soja_ibfk_1;
ALTER TABLE ap_correct_cereales DROP FOREIGN KEY ap_correct_cereales_ibfk_1;
ALTER TABLE ap_correct_soja DROP FOREIGN KEY ap_correct_soja_ibfk_1;
ALTER TABLE av_correct_cereales DROP FOREIGN KEY av_correct_cereales_ibfk_1;
ALTER TABLE av_correct_soja DROP FOREIGN KEY av_correct_soja_ibfk_1;
ALTER TABLE decanteur_cereales DROP FOREIGN KEY decanteur_cereales_ibfk_1;
ALTER TABLE echantillons DROP FOREIGN KEY echantillons_ibfk_1;
ALTER TABLE heure_enzyme DROP FOREIGN KEY heure_enzyme_ibfk_1;
ALTER TABLE okara DROP FOREIGN KEY okara_ibfk_1;
ALTER TABLE production DROP FOREIGN KEY production_ibfk_1;
ALTER TABLE quantite_enzyme DROP FOREIGN KEY quantite_enzyme_ibfk_1;

-- Supprimer l'ancienne table of
DROP TABLE `of`;

-- Recréer les contraintes vers la nouvelle table ordre_fabrication
ALTER TABLE analyse_soja ADD CONSTRAINT analyse_soja_ibfk_1 FOREIGN KEY (of_id) REFERENCES ordre_fabrication(id);
ALTER TABLE ap_correct_cereales ADD CONSTRAINT ap_correct_cereales_ibfk_1 FOREIGN KEY (of_id) REFERENCES ordre_fabrication(id);
ALTER TABLE ap_correct_soja ADD CONSTRAINT ap_correct_soja_ibfk_1 FOREIGN KEY (of_id) REFERENCES ordre_fabrication(id);
ALTER TABLE av_correct_cereales ADD CONSTRAINT av_correct_cereales_ibfk_1 FOREIGN KEY (of_id) REFERENCES ordre_fabrication(id);
ALTER TABLE av_correct_soja ADD CONSTRAINT av_correct_soja_ibfk_1 FOREIGN KEY (of_id) REFERENCES ordre_fabrication(id);
ALTER TABLE decanteur_cereales ADD CONSTRAINT decanteur_cereales_ibfk_1 FOREIGN KEY (of_id) REFERENCES ordre_fabrication(id);
ALTER TABLE echantillons ADD CONSTRAINT echantillons_ibfk_1 FOREIGN KEY (of_id) REFERENCES ordre_fabrication(id);
ALTER TABLE heure_enzyme ADD CONSTRAINT heure_enzyme_ibfk_1 FOREIGN KEY (of_id) REFERENCES ordre_fabrication(id);
ALTER TABLE okara ADD CONSTRAINT okara_ibfk_1 FOREIGN KEY (of_id) REFERENCES ordre_fabrication(id);
ALTER TABLE production ADD CONSTRAINT production_ibfk_1 FOREIGN KEY (of_id) REFERENCES ordre_fabrication(id);
ALTER TABLE quantite_enzyme ADD CONSTRAINT quantite_enzyme_ibfk_1 FOREIGN KEY (of_id) REFERENCES ordre_fabrication(id);
