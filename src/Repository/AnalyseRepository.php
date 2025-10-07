<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour les vues d'analyse et reporting
 */
class AnalyseRepository extends ServiceEntityRepository
{
    private Connection $connection;

    public function __construct(ManagerRegistry $registry)
    {
        $this->connection = $registry->getConnection();
    }

    /**
     * Récupère les données de la vue de suivi de production
     *
     * @return array
     */
    public function getSuiviProduction(): array
    {
        $sql = '
            SELECT 
                production_id,
                production_name,
                production_status,
                nombre_of,
                quantite_totale_prevue,
                quantite_totale_realisee,
                ROUND(taux_realisation, 2) as taux_realisation
            FROM vue_suivi_production
            ORDER BY production_name
        ';

        return $this->connection->fetchAllAssociative($sql);
    }

    /**
     * Récupère les productions avec le meilleur taux de réalisation
     *
     * @param int $limit Nombre de résultats
     * @return array
     */
    public function getTopProductionsByTauxRealisation(int $limit = 5): array
    {
        $sql = '
            SELECT 
                production_name,
                ROUND(taux_realisation, 2) as taux_realisation,
                quantite_totale_realisee,
                quantite_totale_prevue
            FROM vue_suivi_production
            WHERE taux_realisation IS NOT NULL
            ORDER BY taux_realisation DESC
            LIMIT :limit
        ';

        return $this->connection->fetchAllAssociative($sql, ['limit' => $limit]);
    }

    /**
     * Récupère les productions en cours avec leur avancement
     *
     * @return array
     */
    public function getProductionsEnCours(): array
    {
        $sql = '
            SELECT 
                production_name,
                nombre_of,
                quantite_totale_prevue,
                quantite_totale_realisee,
                ROUND(taux_realisation, 2) as taux_realisation
            FROM vue_suivi_production
            WHERE production_status = :status
            ORDER BY production_name
        ';

        return $this->connection->fetchAllAssociative($sql, ['status' => 'En cours']);
    }

    /**
     * Récupère les statistiques globales de production
     *
     * @return array
     */
    public function getStatistiquesGlobales(): array
    {
        $sql = '
            SELECT 
                COUNT(*) as total_productions,
                SUM(nombre_of) as total_of,
                SUM(quantite_totale_prevue) as total_quantite_prevue,
                SUM(quantite_totale_realisee) as total_quantite_realisee,
                ROUND(AVG(taux_realisation), 2) as taux_realisation_moyen
            FROM vue_suivi_production
        ';

        $result = $this->connection->fetchAssociative($sql);
        return $result ?: [];
    }

    /**
     * Récupère les productions groupées par statut
     *
     * @return array
     */
    public function getProductionsParStatut(): array
    {
        $sql = '
            SELECT 
                production_status,
                COUNT(*) as nombre_productions,
                SUM(nombre_of) as total_of,
                ROUND(AVG(taux_realisation), 2) as taux_realisation_moyen
            FROM vue_suivi_production
            GROUP BY production_status
            ORDER BY nombre_productions DESC
        ';

        return $this->connection->fetchAllAssociative($sql);
    }

    /**
     * Récupère une production spécifique avec ses détails
     *
     * @param int $productionId ID de la production
     * @return array|null
     */
    public function getDetailProduction(int $productionId): ?array
    {
        $sql = '
            SELECT 
                production_id,
                production_name,
                production_status,
                nombre_of,
                quantite_totale_prevue,
                quantite_totale_realisee,
                ROUND(taux_realisation, 2) as taux_realisation
            FROM vue_suivi_production
            WHERE production_id = :productionId
        ';

        $result = $this->connection->fetchAssociative($sql, ['productionId' => $productionId]);
        return $result ?: null;
    }

    /**
     * Recherche de productions par nom
     *
     * @param string $searchTerm Terme de recherche
     * @return array
     */
    public function searchProductions(string $searchTerm): array
    {
        $sql = '
            SELECT 
                production_id,
                production_name,
                production_status,
                nombre_of,
                quantite_totale_prevue,
                quantite_totale_realisee,
                ROUND(taux_realisation, 2) as taux_realisation
            FROM vue_suivi_production
            WHERE production_name LIKE :searchTerm
            ORDER BY production_name
        ';

        return $this->connection->fetchAllAssociative($sql, [
            'searchTerm' => '%' . $searchTerm . '%'
        ]);
    }

    /**
     * Récupère les productions avec des problèmes (taux de réalisation faible)
     *
     * @param float $seuilTauxRealisation Seuil en dessous duquel considérer comme problématique
     * @return array
     */
    public function getProductionsProblematiques(float $seuilTauxRealisation = 80.0): array
    {
        $sql = '
            SELECT 
                production_name,
                production_status,
                ROUND(taux_realisation, 2) as taux_realisation,
                quantite_totale_prevue,
                quantite_totale_realisee,
                (quantite_totale_prevue - quantite_totale_realisee) as ecart_quantite
            FROM vue_suivi_production
            WHERE taux_realisation < :seuil 
            AND taux_realisation IS NOT NULL
            ORDER BY taux_realisation ASC
        ';

        return $this->connection->fetchAllAssociative($sql, ['seuil' => $seuilTauxRealisation]);
    }
}
