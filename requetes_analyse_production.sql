-- =====================================================
-- REQUÊTES D'ANALYSE AVEC LA VUE SUIVI PRODUCTION
-- =====================================================

-- 1. Vue d'ensemble de toutes les productions
SELECT 
    production_name,
    production_status,
    nombre_of,
    quantite_totale_prevue,
    quantite_totale_realisee,
    ROUND(taux_realisation, 2) as taux_realisation_pct
FROM vue_suivi_production
ORDER BY production_name;

-- 2. Productions avec le meilleur taux de réalisation
SELECT 
    production_name,
    ROUND(taux_realisation, 2) as taux_realisation_pct,
    quantite_totale_realisee,
    quantite_totale_prevue
FROM vue_suivi_production
WHERE taux_realisation IS NOT NULL
ORDER BY taux_realisation DESC
LIMIT 5;

-- 3. Productions en cours avec leur avancement
SELECT 
    production_name,
    nombre_of,
    quantite_totale_prevue,
    quantite_totale_realisee,
    CASE 
        WHEN quantite_totale_prevue > 0 THEN
            CONCAT(ROUND((quantite_totale_realisee / quantite_totale_prevue) * 100, 1), '%')
        ELSE 'N/A'
    END as avancement
FROM vue_suivi_production
WHERE production_status = 'En cours'
ORDER BY production_name;

-- 4. Statistiques globales
SELECT 
    COUNT(*) as total_productions,
    SUM(nombre_of) as total_of,
    SUM(quantite_totale_prevue) as total_quantite_prevue,
    SUM(quantite_totale_realisee) as total_quantite_realisee,
    ROUND(AVG(taux_realisation), 2) as taux_realisation_moyen
FROM vue_suivi_production;

-- 5. Productions par statut
SELECT 
    production_status,
    COUNT(*) as nombre_productions,
    SUM(nombre_of) as total_of,
    AVG(taux_realisation) as taux_realisation_moyen
FROM vue_suivi_production
GROUP BY production_status
ORDER BY nombre_productions DESC;
