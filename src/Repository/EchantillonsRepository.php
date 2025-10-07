<?php

namespace App\Repository;

use App\Entity\Echantillons;
use App\Entity\Okara;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Echantillons>
 *
 * @method Echantillons|null find($id, $lockMode = null, $lockVersion = null)
 * @method Echantillons|null findOneBy(array $criteria, array $orderBy = null)
 * @method Echantillons[]    findAll()
 * @method Echantillons[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EchantillonsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Echantillons::class);
    }

    public function save(Echantillons $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Echantillons $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Trouve les échantillons par Okara
     */
    public function findByOkara(Okara $okara): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e._okara = :okara')
            ->setParameter('okara', $okara)
            ->orderBy('e._id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les échantillons avec un extrait sec dans une plage
     */
    public function findByExtraitSecRange(float $min, float $max): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('CAST(e._extrait_sec AS DECIMAL(10,2)) BETWEEN :min AND :max')
            ->setParameter('min', $min)
            ->setParameter('max', $max)
            ->orderBy('e._extrait_sec', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les échantillons avec perte de poids significative
     */
    public function findWithSignificantWeightLoss(float $minLossPercentage = 10.0): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e._poids_0 IS NOT NULL')
            ->andWhere('e._poids_2 IS NOT NULL')
            ->andWhere('e._poids_0 > 0')
            ->andWhere('((CAST(e._poids_0 AS DECIMAL(10,2)) - CAST(e._poids_2 AS DECIMAL(10,2))) / CAST(e._poids_0 AS DECIMAL(10,2))) * 100 >= :minLoss')
            ->setParameter('minLoss', $minLossPercentage)
            ->orderBy('e._id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Statistiques des poids
     */
    public function getWeightStats(): array
    {
        $result = $this->createQueryBuilder('e')
            ->select('
                AVG(CAST(e._poids_0 AS DECIMAL(10,2))) as poids_0_moyen,
                AVG(CAST(e._poids_1 AS DECIMAL(10,2))) as poids_1_moyen,
                AVG(CAST(e._poids_2 AS DECIMAL(10,2))) as poids_2_moyen,
                MIN(CAST(e._poids_0 AS DECIMAL(10,2))) as poids_0_min,
                MAX(CAST(e._poids_0 AS DECIMAL(10,2))) as poids_0_max,
                MIN(CAST(e._poids_2 AS DECIMAL(10,2))) as poids_2_min,
                MAX(CAST(e._poids_2 AS DECIMAL(10,2))) as poids_2_max
            ')
            ->andWhere('e._poids_0 IS NOT NULL')
            ->andWhere('e._poids_2 IS NOT NULL')
            ->getQuery()
            ->getSingleResult();

        return [
            'poids_initial' => [
                'moyenne' => round((float) $result['poids_0_moyen'], 2),
                'min' => round((float) $result['poids_0_min'], 2),
                'max' => round((float) $result['poids_0_max'], 2)
            ],
            'poids_intermediaire' => [
                'moyenne' => round((float) $result['poids_1_moyen'], 2)
            ],
            'poids_final' => [
                'moyenne' => round((float) $result['poids_2_moyen'], 2),
                'min' => round((float) $result['poids_2_min'], 2),
                'max' => round((float) $result['poids_2_max'], 2)
            ],
            'perte_moyenne' => round((float) $result['poids_0_moyen'] - (float) $result['poids_2_moyen'], 2)
        ];
    }

    /**
     * Statistiques de l'extrait sec
     */
    public function getExtraitSecStats(): array
    {
        $result = $this->createQueryBuilder('e')
            ->select('
                AVG(CAST(e._extrait_sec AS DECIMAL(10,2))) as extrait_sec_moyen,
                MIN(CAST(e._extrait_sec AS DECIMAL(10,2))) as extrait_sec_min,
                MAX(CAST(e._extrait_sec AS DECIMAL(10,2))) as extrait_sec_max,
                COUNT(e._id) as total_avec_extrait_sec
            ')
            ->andWhere('e._extrait_sec IS NOT NULL')
            ->getQuery()
            ->getSingleResult();

        $totalEchantillons = $this->count([]);

        return [
            'moyenne' => round((float) $result['extrait_sec_moyen'], 2),
            'min' => round((float) $result['extrait_sec_min'], 2),
            'max' => round((float) $result['extrait_sec_max'], 2),
            'total_avec_mesure' => (int) $result['total_avec_extrait_sec'],
            'total_echantillons' => $totalEchantillons,
            'taux_mesure' => $totalEchantillons > 0 ? 
                round(((int) $result['total_avec_extrait_sec'] / $totalEchantillons) * 100, 2) : 0
        ];
    }

    /**
     * Calcule la perte de poids pour chaque échantillon
     */
    public function getWeightLossData(): array
    {
        $echantillons = $this->createQueryBuilder('e')
            ->andWhere('e._poids_0 IS NOT NULL')
            ->andWhere('e._poids_2 IS NOT NULL')
            ->andWhere('CAST(e._poids_0 AS DECIMAL(10,2)) > 0')
            ->orderBy('e._id', 'DESC')
            ->getQuery()
            ->getResult();

        $results = [];
        foreach ($echantillons as $echantillon) {
            $poids0 = (float) $echantillon->getPoids0();
            $poids2 = (float) $echantillon->getPoids2();
            $perte = $poids0 - $poids2;
            $pourcentagePerte = ($perte / $poids0) * 100;

            $results[] = [
                'id' => $echantillon->getId(),
                'poids_initial' => $poids0,
                'poids_final' => $poids2,
                'perte_absolue' => round($perte, 2),
                'perte_pourcentage' => round($pourcentagePerte, 2),
                'okara_id' => $echantillon->getOkara()?->getId()
            ];
        }

        return $results;
    }

    /**
     * Trouve les échantillons avec données complètes
     */
    public function findComplete(): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e._poids_0 IS NOT NULL')
            ->andWhere('e._poids_1 IS NOT NULL')
            ->andWhere('e._poids_2 IS NOT NULL')
            ->andWhere('e._extrait_sec IS NOT NULL')
            ->orderBy('e._id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les échantillons avec données incomplètes
     */
    public function findIncomplete(): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('
                e._poids_0 IS NULL 
                OR e._poids_1 IS NULL 
                OR e._poids_2 IS NULL 
                OR e._extrait_sec IS NULL
            ')
            ->orderBy('e._id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche avec filtres multiples
     */
    public function searchWithFilters(array $filters = []): array
    {
        $qb = $this->createQueryBuilder('e');

        if (isset($filters['extrait_sec_min']) && $filters['extrait_sec_min'] !== '') {
            $qb->andWhere('CAST(e._extrait_sec AS DECIMAL(10,2)) >= :extrait_min')
               ->setParameter('extrait_min', (float) $filters['extrait_sec_min']);
        }

        if (isset($filters['extrait_sec_max']) && $filters['extrait_sec_max'] !== '') {
            $qb->andWhere('CAST(e._extrait_sec AS DECIMAL(10,2)) <= :extrait_max')
               ->setParameter('extrait_max', (float) $filters['extrait_sec_max']);
        }

        if (isset($filters['poids_min']) && $filters['poids_min'] !== '') {
            $qb->andWhere('CAST(e._poids_0 AS DECIMAL(10,2)) >= :poids_min')
               ->setParameter('poids_min', (float) $filters['poids_min']);
        }

        if (isset($filters['poids_max']) && $filters['poids_max'] !== '') {
            $qb->andWhere('CAST(e._poids_0 AS DECIMAL(10,2)) <= :poids_max')
               ->setParameter('poids_max', (float) $filters['poids_max']);
        }

        if (isset($filters['complete_data']) && $filters['complete_data'] !== '') {
            if ($filters['complete_data']) {
                $qb->andWhere('e._poids_0 IS NOT NULL')
                   ->andWhere('e._poids_1 IS NOT NULL')
                   ->andWhere('e._poids_2 IS NOT NULL')
                   ->andWhere('e._extrait_sec IS NOT NULL');
            } else {
                $qb->andWhere('
                    e._poids_0 IS NULL 
                    OR e._poids_1 IS NULL 
                    OR e._poids_2 IS NULL 
                    OR e._extrait_sec IS NULL
                ');
            }
        }

        return $qb->orderBy('e._id', 'DESC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Données pour tableau de bord des échantillons
     */
    public function getDashboardData(): array
    {
        $totalEchantillons = $this->count([]);
        $complete = count($this->findComplete());
        $incomplete = count($this->findIncomplete());
        $weightStats = $this->getWeightStats();
        $extraitSecStats = $this->getExtraitSecStats();
        $significantLoss = $this->findWithSignificantWeightLoss();

        return [
            'total_echantillons' => $totalEchantillons,
            'echantillons_complets' => $complete,
            'echantillons_incomplets' => $incomplete,
            'taux_completion' => $totalEchantillons > 0 ? round(($complete / $totalEchantillons) * 100, 2) : 0,
            'statistiques_poids' => $weightStats,
            'statistiques_extrait_sec' => $extraitSecStats,
            'alertes' => [
                'pertes_significatives' => count($significantLoss),
                'donnees_manquantes' => $incomplete
            ]
        ];
    }

    /**
     * Rapport d'analyse des échantillons
     */
    public function getAnalysisReport(): array
    {
        $dashboard = $this->getDashboardData();
        $weightLossData = $this->getWeightLossData();
        
        // Calcul des moyennes de perte
        $totalLoss = array_sum(array_column($weightLossData, 'perte_pourcentage'));
        $avgLossPercentage = count($weightLossData) > 0 ? 
            round($totalLoss / count($weightLossData), 2) : 0;

        return [
            'resume' => [
                'total_echantillons' => $dashboard['total_echantillons'],
                'taux_completion' => $dashboard['taux_completion'],
                'perte_moyenne_pourcentage' => $avgLossPercentage
            ],
            'qualite_donnees' => [
                'complets' => $dashboard['echantillons_complets'],
                'incomplets' => $dashboard['echantillons_incomplets'],
                'taux_extrait_sec' => $dashboard['statistiques_extrait_sec']['taux_mesure']
            ],
            'analyse_poids' => $dashboard['statistiques_poids'],
            'analyse_extrait_sec' => $dashboard['statistiques_extrait_sec'],
            'pertes_poids' => [
                'moyenne_pourcentage' => $avgLossPercentage,
                'echantillons_perte_significative' => count($dashboard['alertes']['pertes_significatives']),
                'details_pertes' => array_slice($weightLossData, 0, 10) // Top 10
            ],
            'recommandations' => [
                'qualite_donnees' => $dashboard['taux_completion'] < 80 ? 
                    'Améliorer la saisie des données complètes' : 'Qualité des données satisfaisante',
                'controle_pertes' => $avgLossPercentage > 15 ? 
                    'Analyser les causes de pertes importantes' : 'Pertes dans les normes'
            ]
        ];
    }
}
