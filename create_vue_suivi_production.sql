-- =====================================================
-- CRÉATION DE LA VUE DE SUIVI DE PRODUCTION
-- Base de données: crea_analysis_recorder
-- Date: 7 octobre 2025
-- =====================================================

-- Vérifier que nous sommes dans la bonne base de données
USE `crea_analysis_recorder`;

-- Supprimer la vue si elle existe déjà
DROP VIEW IF EXISTS `vue_suivi_production`;

-- Créer la vue pour le suivi de production
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

-- Vérifier que la vue a été créée
SHOW CREATE VIEW `vue_suivi_production`;

-- Test de la vue avec des données
SELECT 'Test de la vue vue_suivi_production:' as Message;
SELECT * FROM `vue_suivi_production`;

-- Message de confirmation
SELECT 'Vue vue_suivi_production créée avec succès !' as Resultat;
