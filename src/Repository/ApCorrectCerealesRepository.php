<?php

namespace App\Repository;

use App\Entity\ApCorrectCereales;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApCorrectCereales>
 *
 * @method ApCorrectCereales|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApCorrectCereales|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApCorrectCereales[]    findAll()
 * @method ApCorrectCereales[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApCorrectCerealesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApCorrectCereales::class);
    }

    public function save(ApCorrectCereales $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ApCorrectCereales $entity, bool $flush = false): void
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
        return $this->createQueryBuilder('ac')
            ->andWhere('ac.tank = :tank')
            ->setParameter('tank', $tank)
            ->orderBy('ac.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les corrections par plage de dates
     */
    public function findByDateRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('ac')
            ->andWhere('ac.date BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->orderBy('ac.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les corrections avec eau ajoutée supérieure à un seuil
     */
    public function findWithHighWaterAddition(int $minWaterAmount = 100): array
    {
        return $this->createQueryBuilder('ac')
            ->andWhere('ac.eauAjouter >= :minWater')
            ->setParameter('minWater', $minWaterAmount)
            ->orderBy('ac.eauAjouter', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Statistiques des corrections par tank
     */
    public function getStatisticsByTank(): array
    {
        $results = $this->createQueryBuilder('ac')
            ->select('
                ac.tank,
                COUNT(ac.id) as nb_corrections,
                AVG(ac.eauAjouter) as eau_moyenne,
                AVG(ac.produitFini) as produit_fini_moyen,
                AVG(CAST(ac.esTank AS DECIMAL(10,2))) as es_tank_moyen,
                AVG(CAST(ac.culot AS DECIMAL(10,2))) as culot_moyen
            ')
            ->groupBy('ac.tank')
            ->orderBy('ac.tank', 'ASC')
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
     * Corrections récentes (dernières 30 jours)
     */
    public function findRecent(int $days = 30): array
    {
        $startDate = new \DateTime();
        $startDate->modify("-{$days} days");

        return $this->createQueryBuilder('ac')
            ->andWhere('ac.date >= :startDate')
            ->setParameter('startDate', $startDate)
            ->orderBy('ac.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Corrections par mois
     */
    public function getMonthlyStats(): array
    {
        $results = $this->createQueryBuilder('ac')
            ->select('
                YEAR(ac.date) as annee,
                MONTH(ac.date) as mois,
                COUNT(ac.id) as nb_corrections,
                AVG(ac.eauAjouter) as eau_moyenne,
                SUM(ac.eauAjouter) as eau_totale
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
     * Recherche avec filtres multiples
     */
    public function searchWithFilters(array $filters = []): array
    {
        $qb = $this->createQueryBuilder('ac');

        if (isset($filters['tank']) && $filters['tank'] !== '') {
            $qb->andWhere('ac.tank = :tank')
               ->setParameter('tank', (int) $filters['tank']);
        }

        if (isset($filters['date_start']) && $filters['date_start'] !== '') {
            $qb->andWhere('ac.date >= :dateStart')
               ->setParameter('dateStart', new \DateTime($filters['date_start']));
        }

        if (isset($filters['date_end']) && $filters['date_end'] !== '') {
            $qb->andWhere('ac.date <= :dateEnd')
               ->setParameter('dateEnd', new \DateTime($filters['date_end']));
        }

        if (isset($filters['eau_min']) && $filters['eau_min'] !== '') {
            $qb->andWhere('ac.eauAjouter >= :eauMin')
               ->setParameter('eauMin', (int) $filters['eau_min']);
        }

        if (isset($filters['produit_fini_min']) && $filters['produit_fini_min'] !== '') {
            $qb->andWhere('ac.produitFini >= :produitMin')
               ->setParameter('produitMin', (int) $filters['produit_fini_min']);
        }

        return $qb->orderBy('ac.date', 'DESC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Tanks les plus utilisés pour corrections
     */
    public function getMostUsedTanks(int $limit = 10): array
    {
        $results = $this->createQueryBuilder('ac')
            ->select('ac.tank, COUNT(ac.id) as nb_corrections')
            ->groupBy('ac.tank')
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
     * Données pour tableau de bord corrections céréales
     */
    public function getDashboardData(): array
    {
        $total = $this->count([]);
        $recent = $this->findRecent();
        $tankStats = $this->getStatisticsByTank();
        $mostUsedTanks = $this->getMostUsedTanks(5);
        $monthlyStats = $this->getMonthlyStats();

        // Calculs généraux
        $totalEauAjoutee = $this->createQueryBuilder('ac')
            ->select('SUM(ac.eauAjouter) as total')
            ->getQuery()
            ->getSingleScalarResult();

        $moyenneEau = $this->createQueryBuilder('ac')
            ->select('AVG(ac.eauAjouter) as moyenne')
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
     * Rapport d'activité corrections céréales
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
                'optimisation' => count($dashboard['tanks_plus_utilises']) > 0 ? 
                    'Analyser les tanks nécessitant le plus de corrections' : 'Pas de corrections fréquentes détectées',
                'maintenance' => $dashboard['eau_moyenne_par_correction'] > 200 ? 
                    'Vérifier les processus - ajouts d\'eau importants' : 'Niveaux d\'ajout normaux'
            ]
        ];
    }
}
