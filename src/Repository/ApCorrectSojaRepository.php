<?php

namespace App\Repository;

use App\Entity\ApCorrectSoja;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApCorrectSoja>
 *
 * @method ApCorrectSoja|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApCorrectSoja|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApCorrectSoja[]    findAll()
 * @method ApCorrectSoja[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApCorrectSojaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApCorrectSoja::class);
    }

    public function save(ApCorrectSoja $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ApCorrectSoja $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Trouve les corrections par tank
     */
    public function findByTank(int $tank): array
    {
        return $this->createQueryBuilder('as')
            ->andWhere('as.tank = :tank')
            ->setParameter('tank', $tank)
            ->orderBy('as.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les corrections par plage de dates
     */
    public function findByDateRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('as')
            ->andWhere('as.date BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->orderBy('as.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les corrections avec eau ajoutée supérieure à un seuil
     */
    public function findWithHighWaterAddition(int $minWaterAmount = 100): array
    {
        return $this->createQueryBuilder('as')
            ->andWhere('as.eauAjouter >= :minWater')
            ->setParameter('minWater', $minWaterAmount)
            ->orderBy('as.eauAjouter', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Statistiques des corrections par tank pour soja
     */
    public function getStatisticsByTank(): array
    {
        $results = $this->createQueryBuilder('as')
            ->select('
                as.tank,
                COUNT(as.id) as nb_corrections,
                AVG(as.eauAjouter) as eau_moyenne,
                AVG(as.produitFini) as produit_fini_moyen,
                AVG(CAST(as.esTank AS DECIMAL(10,2))) as es_tank_moyen,
                AVG(CAST(as.culot AS DECIMAL(10,2))) as culot_moyen
            ')
            ->groupBy('as.tank')
            ->orderBy('as.tank', 'ASC')
            ->getQuery()
            ->getResult();

        return array_map(function ($result) {
            return [
                'tank' => $result['tank'],
                'nb_corrections' => (int) $result['nb_corrections'],
                'eau_moyenne' => round((float) $result['eau_moyenne'], 2),
                'produit_fini_moyen' => round((float) $result['produit_fini_moyen'], 2),
                'es_tank_moyen' => round((float) $result['es_tank_moyen'], 2),
                'culot_moyen' => round((float) $result['culot_moyen'], 2)
            ];
        }, $results);
    }

    /**
     * Corrections récentes soja (dernières 30 jours)
     */
    public function findRecent(int $days = 30): array
    {
        $startDate = new \DateTime();
        $startDate->modify("-{$days} days");

        return $this->createQueryBuilder('as')
            ->andWhere('as.date >= :startDate')
            ->setParameter('startDate', $startDate)
            ->orderBy('as.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Corrections par mois pour soja
     */
    public function getMonthlyStats(): array
    {
        $results = $this->createQueryBuilder('as')
            ->select('
                YEAR(as.date) as annee,
                MONTH(as.date) as mois,
                COUNT(as.id) as nb_corrections,
                AVG(as.eauAjouter) as eau_moyenne,
                SUM(as.eauAjouter) as eau_totale
            ')
            ->groupBy('annee, mois')
            ->orderBy('annee', 'DESC')
            ->addOrderBy('mois', 'DESC')
            ->getQuery()
            ->getResult();

        return array_map(function ($result) {
            return [
                'periode' => $result['annee'] . '-' . str_pad($result['mois'], 2, '0', STR_PAD_LEFT),
                'nb_corrections' => (int) $result['nb_corrections'],
                'eau_moyenne' => round((float) $result['eau_moyenne'], 2),
                'eau_totale' => (int) $result['eau_totale']
            ];
        }, $results);
    }

    /**
     * Recherche avec filtres multiples pour soja
     */
    public function searchWithFilters(array $filters = []): array
    {
        $qb = $this->createQueryBuilder('as');

        if (isset($filters['tank']) && $filters['tank'] !== '') {
            $qb->andWhere('as.tank = :tank')
               ->setParameter('tank', (int) $filters['tank']);
        }

        if (isset($filters['date_start']) && $filters['date_start'] !== '') {
            $qb->andWhere('as.date >= :dateStart')
               ->setParameter('dateStart', new \DateTime($filters['date_start']));
        }

        if (isset($filters['date_end']) && $filters['date_end'] !== '') {
            $qb->andWhere('as.date <= :dateEnd')
               ->setParameter('dateEnd', new \DateTime($filters['date_end']));
        }

        if (isset($filters['eau_min']) && $filters['eau_min'] !== '') {
            $qb->andWhere('as.eauAjouter >= :eauMin')
               ->setParameter('eauMin', (int) $filters['eau_min']);
        }

        if (isset($filters['produit_fini_min']) && $filters['produit_fini_min'] !== '') {
            $qb->andWhere('as.produitFini >= :produitMin')
               ->setParameter('produitMin', (int) $filters['produit_fini_min']);
        }

        return $qb->orderBy('as.date', 'DESC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Tanks les plus utilisés pour corrections soja
     */
    public function getMostUsedTanks(int $limit = 10): array
    {
        $results = $this->createQueryBuilder('as')
            ->select('as.tank, COUNT(as.id) as nb_corrections')
            ->groupBy('as.tank')
            ->orderBy('nb_corrections', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return array_map(function ($result) {
            return [
                'tank' => $result['tank'],
                'nb_corrections' => (int) $result['nb_corrections']
            ];
        }, $results);
    }

    /**
     * Données pour tableau de bord corrections soja
     */
    public function getDashboardData(): array
    {
        $total = $this->count([]);
        $recent = $this->findRecent();
        $tankStats = $this->getStatisticsByTank();
        $mostUsedTanks = $this->getMostUsedTanks(5);
        $monthlyStats = $this->getMonthlyStats();

        // Calculs généraux
        $totalEauAjoutee = $this->createQueryBuilder('as')
            ->select('SUM(as.eauAjouter) as total')
            ->getQuery()
            ->getSingleScalarResult();

        $moyenneEau = $this->createQueryBuilder('as')
            ->select('AVG(as.eauAjouter) as moyenne')
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'total_corrections' => $total,
            'corrections_recentes' => count($recent),
            'eau_totale_ajoutee' => (int) $totalEauAjoutee,
            'eau_moyenne_par_correction' => round((float) $moyenneEau, 2),
            'statistiques_par_tank' => $tankStats,
            'tanks_plus_utilises' => $mostUsedTanks,
            'evolution_mensuelle' => array_slice($monthlyStats, 0, 12),
            'resume' => [
                'nb_tanks_utilises' => count($tankStats),
                'corrections_mois_courant' => count($recent)
            ]
        ];
    }

    /**
     * Rapport d'activité corrections soja
     */
    public function getActivityReport(): array
    {
        $dashboard = $this->getDashboardData();
        
        return [
            'resume_general' => [
                'total_corrections' => $dashboard['total_corrections'],
                'eau_totale_ajoutee' => $dashboard['eau_totale_ajoutee'],
                'eau_moyenne' => $dashboard['eau_moyenne_par_correction']
            ],
            'performance_tanks' => $dashboard['statistiques_par_tank'],
            'utilisation' => [
                'tanks_actifs' => $dashboard['resume']['nb_tanks_utilises'],
                'corrections_mensuelles' => $dashboard['resume']['corrections_mois_courant']
            ],
            'tendances' => $dashboard['evolution_mensuelle'],
            'recommandations' => [
                'optimisation_soja' => count($dashboard['tanks_plus_utilises']) > 0 ? 
                    'Analyser les tanks soja nécessitant le plus de corrections' : 'Pas de corrections fréquentes détectées',
                'maintenance_soja' => $dashboard['eau_moyenne_par_correction'] > 150 ? 
                    'Vérifier les processus soja - ajouts d\'eau importants' : 'Niveaux d\'ajout normaux pour soja'
            ]
        ];
    }

    /**
     * Comparaison avec les corrections céréales (méthode utilitaire)
     */
    public function getComparisonWithCereales(): array
    {
        $sojaDashboard = $this->getDashboardData();
        
        return [
            'soja' => [
                'total_corrections' => $sojaDashboard['total_corrections'],
                'eau_moyenne' => $sojaDashboard['eau_moyenne_par_correction'],
                'nb_tanks_actifs' => $sojaDashboard['resume']['nb_tanks_utilises']
            ],
            'type_produit' => 'soja'
        ];
    }
}
