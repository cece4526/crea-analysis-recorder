<?php

namespace App\Repository;

use App\Entity\CuveCereales;
use App\Entity\OF;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CuveCereales>
 *
 * @method CuveCereales|null find($id, $lockMode = null, $lockVersion = null)
 * @method CuveCereales|null findOneBy(array $criteria, array $orderBy = null)
 * @method CuveCereales[]    findAll()
 * @method CuveCereales[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CuveCerealesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CuveCereales::class);
    }

    public function save(CuveCereales $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CuveCereales $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Trouve les cuves par OF
     */
    public function findByOF(OF $of): array
    {
        return $this->createQueryBuilder('cc')
            ->andWhere('cc.of = :of')
            ->setParameter('of', $of)
            ->orderBy('cc.cuve', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les cuves par numéro de cuve
     */
    public function findByCuveNumber(int $cuveNumber): array
    {
        return $this->createQueryBuilder('cc')
            ->andWhere('cc.cuve = :cuve')
            ->setParameter('cuve', $cuveNumber)
            ->orderBy('cc.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les cuves avec contrôle verre défaillant
     */
    public function findWithFailedGlassControl(): array
    {
        return $this->createQueryBuilder('cc')
            ->andWhere('cc.control_verre = :failed')
            ->setParameter('failed', false)
            ->orderBy('cc.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les cuves avec température d'hydrolyse hors normes
     */
    public function findWithTemperatureOutOfRange(float $minTemp = 50.0, float $maxTemp = 70.0): array
    {
        return $this->createQueryBuilder('cc')
            ->andWhere('
                CAST(cc.temperature_hydrolise AS DECIMAL(10,2)) < :minTemp 
                OR CAST(cc.temperature_hydrolise AS DECIMAL(10,2)) > :maxTemp
            ')
            ->setParameter('minTemp', $minTemp)
            ->setParameter('maxTemp', $maxTemp)
            ->orderBy('cc.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Statistiques par numéro de cuve
     */
    public function getStatisticsByCuve(): array
    {
        $results = $this->createQueryBuilder('cc')
            ->select('
                cc.cuve,
                COUNT(cc.id) as nb_utilisations,
                AVG(CAST(cc.debit_enzyme AS DECIMAL(10,2))) as debit_enzyme_moyen,
                AVG(CAST(cc.temperature_hydrolise AS DECIMAL(10,2))) as temperature_moyenne,
                SUM(CASE WHEN cc.control_verre = true THEN 1 ELSE 0 END) as controles_verre_ok,
                SUM(CASE WHEN cc.control_verre = false THEN 1 ELSE 0 END) as controles_verre_ko
            ')
            ->groupBy('cc.cuve')
            ->orderBy('cc.cuve', 'ASC')
            ->getQuery()
            ->getResult();

        return array_map(function ($result) {
            $totalControles = (int) $result['controles_verre_ok'] + (int) $result['controles_verre_ko'];
            return [
                'cuve' => $result['cuve'],
                'nb_utilisations' => (int) $result['nb_utilisations'],
                'debit_enzyme_moyen' => round((float) $result['debit_enzyme_moyen'], 2),
                'temperature_moyenne' => round((float) $result['temperature_moyenne'], 2),
                'controles_verre_ok' => (int) $result['controles_verre_ok'],
                'controles_verre_ko' => (int) $result['controles_verre_ko'],
                'taux_controle_verre_ok' => $totalControles > 0 ? 
                    round(((int) $result['controles_verre_ok'] / $totalControles) * 100, 2) : 0
            ];
        }, $results);
    }

    /**
     * Cuves les plus utilisées
     */
    public function getMostUsedCuves(int $limit = 10): array
    {
        $results = $this->createQueryBuilder('cc')
            ->select('cc.cuve, COUNT(cc.id) as nb_utilisations')
            ->groupBy('cc.cuve')
            ->orderBy('nb_utilisations', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return array_map(function ($result) {
            return [
                'cuve' => $result['cuve'],
                'nb_utilisations' => (int) $result['nb_utilisations']
            ];
        }, $results);
    }

    /**
     * Analyse des débits d'enzyme
     */
    public function getEnzymeFlowAnalysis(): array
    {
        $result = $this->createQueryBuilder('cc')
            ->select('
                AVG(CAST(cc.debit_enzyme AS DECIMAL(10,2))) as debit_moyen,
                MIN(CAST(cc.debit_enzyme AS DECIMAL(10,2))) as debit_min,
                MAX(CAST(cc.debit_enzyme AS DECIMAL(10,2))) as debit_max,
                COUNT(cc.id) as total_mesures
            ')
            ->andWhere('cc.debit_enzyme IS NOT NULL')
            ->getQuery()
            ->getSingleResult();

        return [
            'debit_moyen' => round((float) $result['debit_moyen'], 2),
            'debit_min' => round((float) $result['debit_min'], 2),
            'debit_max' => round((float) $result['debit_max'], 2),
            'total_mesures' => (int) $result['total_mesures']
        ];
    }

    /**
     * Analyse des températures d'hydrolyse
     */
    public function getTemperatureAnalysis(): array
    {
        $result = $this->createQueryBuilder('cc')
            ->select('
                AVG(CAST(cc.temperature_hydrolise AS DECIMAL(10,2))) as temp_moyenne,
                MIN(CAST(cc.temperature_hydrolise AS DECIMAL(10,2))) as temp_min,
                MAX(CAST(cc.temperature_hydrolise AS DECIMAL(10,2))) as temp_max,
                COUNT(cc.id) as total_mesures
            ')
            ->andWhere('cc.temperature_hydrolise IS NOT NULL')
            ->getQuery()
            ->getSingleResult();

        return [
            'temperature_moyenne' => round((float) $result['temp_moyenne'], 2),
            'temperature_min' => round((float) $result['temp_min'], 2),
            'temperature_max' => round((float) $result['temp_max'], 2),
            'total_mesures' => (int) $result['total_mesures']
        ];
    }

    /**
     * Recherche avec filtres multiples
     */
    public function searchWithFilters(array $filters = []): array
    {
        $qb = $this->createQueryBuilder('cc');

        if (isset($filters['cuve']) && $filters['cuve'] !== '') {
            $qb->andWhere('cc.cuve = :cuve')
               ->setParameter('cuve', (int) $filters['cuve']);
        }

        if (isset($filters['control_verre']) && $filters['control_verre'] !== '') {
            $qb->andWhere('cc.control_verre = :controlVerre')
               ->setParameter('controlVerre', (bool) $filters['control_verre']);
        }

        if (isset($filters['temp_min']) && $filters['temp_min'] !== '') {
            $qb->andWhere('CAST(cc.temperature_hydrolise AS DECIMAL(10,2)) >= :tempMin')
               ->setParameter('tempMin', (float) $filters['temp_min']);
        }

        if (isset($filters['temp_max']) && $filters['temp_max'] !== '') {
            $qb->andWhere('CAST(cc.temperature_hydrolise AS DECIMAL(10,2)) <= :tempMax')
               ->setParameter('tempMax', (float) $filters['temp_max']);
        }

        if (isset($filters['debit_min']) && $filters['debit_min'] !== '') {
            $qb->andWhere('CAST(cc.debit_enzyme AS DECIMAL(10,2)) >= :debitMin')
               ->setParameter('debitMin', (float) $filters['debit_min']);
        }

        return $qb->orderBy('cc.id', 'DESC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Données pour tableau de bord cuves céréales
     */
    public function getDashboardData(): array
    {
        $total = $this->count([]);
        $cuveStats = $this->getStatisticsByCuve();
        $mostUsedCuves = $this->getMostUsedCuves(5);
        $failedGlassControl = $this->findWithFailedGlassControl();
        $temperatureOutOfRange = $this->findWithTemperatureOutOfRange();
        $enzymeAnalysis = $this->getEnzymeFlowAnalysis();
        $temperatureAnalysis = $this->getTemperatureAnalysis();

        // Calculs généraux
        $totalGlassControls = $this->createQueryBuilder('cc')
            ->select('COUNT(cc.id) as total')
            ->andWhere('cc.control_verre IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult();

        $successfulGlassControls = $this->createQueryBuilder('cc')
            ->select('COUNT(cc.id) as total')
            ->andWhere('cc.control_verre = :success')
            ->setParameter('success', true)
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'total_operations' => $total,
            'nb_cuves_actives' => count($cuveStats),
            'statistiques_par_cuve' => $cuveStats,
            'cuves_plus_utilisees' => $mostUsedCuves,
            'controles_verre' => [
                'total' => (int) $totalGlassControls,
                'reussis' => (int) $successfulGlassControls,
                'echoues' => count($failedGlassControl),
                'taux_reussite' => (int) $totalGlassControls > 0 ? 
                    round(((int) $successfulGlassControls / (int) $totalGlassControls) * 100, 2) : 0
            ],
            'analyse_enzymes' => $enzymeAnalysis,
            'analyse_temperatures' => $temperatureAnalysis,
            'alertes' => [
                'controles_verre_echoues' => count($failedGlassControl),
                'temperatures_anormales' => count($temperatureOutOfRange)
            ]
        ];
    }

    /**
     * Rapport d'activité cuves céréales
     */
    public function getActivityReport(): array
    {
        $dashboard = $this->getDashboardData();
        
        return [
            'resume_general' => [
                'total_operations' => $dashboard['total_operations'],
                'cuves_actives' => $dashboard['nb_cuves_actives'],
                'taux_controle_verre' => $dashboard['controles_verre']['taux_reussite']
            ],
            'performance_cuves' => $dashboard['statistiques_par_cuve'],
            'utilisation' => [
                'cuves_plus_utilisees' => $dashboard['cuves_plus_utilisees'],
                'repartition_utilisation' => array_slice($dashboard['statistiques_par_cuve'], 0, 10)
            ],
            'qualite_processus' => [
                'controles_verre' => $dashboard['controles_verre'],
                'parametres_enzymes' => $dashboard['analyse_enzymes'],
                'parametres_temperatures' => $dashboard['analyse_temperatures']
            ],
            'alertes' => $dashboard['alertes'],
            'recommandations' => [
                'maintenance_cuves' => $dashboard['controles_verre']['taux_reussite'] < 95 ? 
                    'Améliorer la maintenance des contrôles verre' : 'Qualité contrôles verre satisfaisante',
                'optimisation_temperatures' => count($dashboard['alertes']['temperatures_anormales']) > 0 ? 
                    'Vérifier la régulation des températures d\'hydrolyse' : 'Températures dans les normes',
                'gestion_enzymes' => $dashboard['analyse_enzymes']['debit_moyen'] > 0 ? 
                    'Surveiller la consommation d\'enzymes' : 'Analyser les débits d\'enzymes'
            ]
        ];
    }
}
