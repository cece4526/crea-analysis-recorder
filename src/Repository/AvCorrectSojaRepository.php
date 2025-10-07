<?php

namespace App\Repository;

use App\Entity\AvCorrectSoja;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AvCorrectSoja>
 *
 * @method AvCorrectSoja|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvCorrectSoja|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvCorrectSoja[]    findAll()
 * @method AvCorrectSoja[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvCorrectSojaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvCorrectSoja::class);
    }

    public function save(AvCorrectSoja $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AvCorrectSoja $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Trouve les données pré-correction soja par tank
     */
    public function findByTank(int $tank): array
    {
        return $this->createQueryBuilder('avs')
            ->andWhere('avs.tank = :tank')
            ->setParameter('tank', $tank)
            ->orderBy('avs.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les données par plage de dates
     */
    public function findByDateRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('avs')
            ->andWhere('avs.date BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->orderBy('avs.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les mesures soja avec ES faible
     */
    public function findWithLowES(float $maxES = 8.0): array
    {
        return $this->createQueryBuilder('avs')
            ->andWhere('CAST(avs.esTank AS DECIMAL(10,2)) <= :maxES')
            ->setParameter('maxES', $maxES)
            ->orderBy('avs.esTank', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Statistiques par tank avant correction soja
     */
    public function getPreCorrectionStatsByTank(): array
    {
        $results = $this->createQueryBuilder('avs')
            ->select('
                avs.tank,
                COUNT(avs.id) as nb_mesures,
                AVG(CAST(avs.esTank AS DECIMAL(10,2))) as es_tank_moyen,
                MIN(CAST(avs.esTank AS DECIMAL(10,2))) as es_tank_min,
                MAX(CAST(avs.esTank AS DECIMAL(10,2))) as es_tank_max,
                AVG(CAST(avs.culot AS DECIMAL(10,2))) as culot_moyen
            ')
            ->groupBy('avs.tank')
            ->orderBy('avs.tank', 'ASC')
            ->getQuery()
            ->getResult();

        return array_map(function ($result) {
            return [
                'tank' => $result['tank'],
                'nb_mesures' => (int) $result['nb_mesures'],
                'es_tank_moyen' => round((float) $result['es_tank_moyen'], 2),
                'es_tank_min' => round((float) $result['es_tank_min'], 2),
                'es_tank_max' => round((float) $result['es_tank_max'], 2),
                'culot_moyen' => round((float) $result['culot_moyen'], 2)
            ];
        }, $results);
    }

    /**
     * Données récentes avant correction soja (30 jours)
     */
    public function findRecent(int $days = 30): array
    {
        $startDate = new \DateTime();
        $startDate->modify("-{$days} days");

        return $this->createQueryBuilder('avs')
            ->andWhere('avs.date >= :startDate')
            ->setParameter('startDate', $startDate)
            ->orderBy('avs.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Evolution mensuelle des mesures pré-correction soja
     */
    public function getMonthlyPreCorrectionStats(): array
    {
        $results = $this->createQueryBuilder('avs')
            ->select('
                YEAR(avs.date) as annee,
                MONTH(avs.date) as mois,
                COUNT(avs.id) as nb_mesures,
                AVG(CAST(avs.esTank AS DECIMAL(10,2))) as es_moyen,
                AVG(CAST(avs.culot AS DECIMAL(10,2))) as culot_moyen
            ')
            ->groupBy('annee, mois')
            ->orderBy('annee', 'DESC')
            ->addOrderBy('mois', 'DESC')
            ->getQuery()
            ->getResult();

        return array_map(function ($result) {
            return [
                'periode' => $result['annee'] . '-' . str_pad($result['mois'], 2, '0', STR_PAD_LEFT),
                'nb_mesures' => (int) $result['nb_mesures'],
                'es_moyen' => round((float) $result['es_moyen'], 2),
                'culot_moyen' => round((float) $result['culot_moyen'], 2)
            ];
        }, $results);
    }

    /**
     * Recherche avec filtres multiples soja
     */
    public function searchWithFilters(array $filters = []): array
    {
        $qb = $this->createQueryBuilder('avs');

        if (isset($filters['tank']) && $filters['tank'] !== '') {
            $qb->andWhere('avs.tank = :tank')
               ->setParameter('tank', (int) $filters['tank']);
        }

        if (isset($filters['date_start']) && $filters['date_start'] !== '') {
            $qb->andWhere('avs.date >= :dateStart')
               ->setParameter('dateStart', new \DateTime($filters['date_start']));
        }

        if (isset($filters['date_end']) && $filters['date_end'] !== '') {
            $qb->andWhere('avs.date <= :dateEnd')
               ->setParameter('dateEnd', new \DateTime($filters['date_end']));
        }

        if (isset($filters['es_min']) && $filters['es_min'] !== '') {
            $qb->andWhere('CAST(avs.esTank AS DECIMAL(10,2)) >= :esMin')
               ->setParameter('esMin', (float) $filters['es_min']);
        }

        if (isset($filters['es_max']) && $filters['es_max'] !== '') {
            $qb->andWhere('CAST(avs.esTank AS DECIMAL(10,2)) <= :esMax')
               ->setParameter('esMax', (float) $filters['es_max']);
        }

        return $qb->orderBy('avs.date', 'DESC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Tanks soja nécessitant le plus de corrections (ES faible)
     */
    public function getTanksNeedingCorrection(float $thresholdES = 9.0): array
    {
        $results = $this->createQueryBuilder('avs')
            ->select('avs.tank, COUNT(avs.id) as nb_mesures_faibles')
            ->andWhere('CAST(avs.esTank AS DECIMAL(10,2)) <= :threshold')
            ->setParameter('threshold', $thresholdES)
            ->groupBy('avs.tank')
            ->orderBy('nb_mesures_faibles', 'DESC')
            ->getQuery()
            ->getResult();

        return array_map(function ($result) {
            return [
                'tank' => $result['tank'],
                'nb_mesures_es_faible' => (int) $result['nb_mesures_faibles']
            ];
        }, $results);
    }

    /**
     * Données pour tableau de bord pré-correction soja
     */
    public function getDashboardData(): array
    {
        $total = $this->count([]);
        $recent = $this->findRecent();
        $tankStats = $this->getPreCorrectionStatsByTank();
        $lowESMeasures = $this->findWithLowES();
        $tanksNeedingCorrection = $this->getTanksNeedingCorrection();
        $monthlyStats = $this->getMonthlyPreCorrectionStats();

        // Statistiques générales
        $avgES = $this->createQueryBuilder('avs')
            ->select('AVG(CAST(avs.esTank AS DECIMAL(10,2))) as moyenne')
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'total_mesures' => $total,
            'mesures_recentes' => count($recent),
            'es_moyen_global' => round((float) $avgES, 2),
            'mesures_es_faible' => count($lowESMeasures),
            'statistiques_par_tank' => $tankStats,
            'tanks_necessitant_correction' => $tanksNeedingCorrection,
            'evolution_mensuelle' => array_slice($monthlyStats, 0, 12),
            'resume' => [
                'nb_tanks_mesures' => count($tankStats),
                'mesures_mois_courant' => count($recent),
                'taux_es_faible' => $total > 0 ? round((count($lowESMeasures) / $total) * 100, 2) : 0
            ]
        ];
    }

    /**
     * Rapport d'analyse pré-correction soja
     */
    public function getPreCorrectionReport(): array
    {
        $dashboard = $this->getDashboardData();
        
        return [
            'resume_general' => [
                'total_mesures' => $dashboard['total_mesures'],
                'es_moyen_global' => $dashboard['es_moyen_global'],
                'taux_es_faible' => $dashboard['resume']['taux_es_faible']
            ],
            'qualite_production_soja' => [
                'mesures_conformes' => $dashboard['total_mesures'] - $dashboard['mesures_es_faible'],
                'mesures_non_conformes' => $dashboard['mesures_es_faible'],
                'tanks_actifs' => $dashboard['resume']['nb_tanks_mesures']
            ],
            'analyse_par_tank' => $dashboard['statistiques_par_tank'],
            'alertes_soja' => [
                'tanks_problematiques' => count($dashboard['tanks_necessitant_correction']),
                'details_tanks' => $dashboard['tanks_necessitant_correction']
            ],
            'tendances' => $dashboard['evolution_mensuelle'],
            'recommandations' => [
                'qualite_soja' => $dashboard['resume']['taux_es_faible'] > 25 ? 
                    'Taux élevé de mesures ES faible sur soja - vérifier processus' : 'Qualité ES soja satisfaisante',
                'maintenance_soja' => count($dashboard['tanks_necessitant_correction']) > 2 ? 
                    'Plusieurs tanks soja nécessitent attention' : 'Performance tanks soja acceptable'
            ]
        ];
    }

    /**
     * Comparaison avec céréales (méthode utilitaire)
     */
    public function getComparisonWithCereales(): array
    {
        $sojaDashboard = $this->getDashboardData();
        
        return [
            'soja_pre_correction' => [
                'total_mesures' => $sojaDashboard['total_mesures'],
                'es_moyen' => $sojaDashboard['es_moyen_global'],
                'taux_es_faible' => $sojaDashboard['resume']['taux_es_faible']
            ],
            'type_produit' => 'soja_avant_correction'
        ];
    }
}
