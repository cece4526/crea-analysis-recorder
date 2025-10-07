<?php

namespace App\Repository;

use App\Entity\Okara;
use App\Entity\OF;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Okara>
 *
 * @method Okara|null find($id, $lockMode = null, $lockVersion = null)
 * @method Okara|null findOneBy(array $criteria, array $orderBy = null)
 * @method Okara[]    findAll()
 * @method Okara[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OkaraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Okara::class);
    }

    public function save(Okara $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Okara $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Trouve un okara par OF
     */
    public function findByOF(OF $of): ?Okara
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.of = :of')
            ->setParameter('of', $of)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Trouve tous les okaras avec leurs échantillons
     */
    public function findWithEchantillons(): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.echantillons', 'e')
            ->addSelect('e')
            ->orderBy('o._id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve un okara avec ses échantillons par ID
     */
    public function findWithEchantillonsById(int $id): ?Okara
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.echantillons', 'e')
            ->addSelect('e')
            ->andWhere('o._id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Trouve les okaras avec un nombre minimum d'échantillons
     */
    public function findWithMinimumEchantillons(int $minCount = 1): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.echantillons', 'e')
            ->groupBy('o._id')
            ->having('COUNT(e._id) >= :minCount')
            ->setParameter('minCount', $minCount)
            ->orderBy('o._id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les okaras sans échantillons
     */
    public function findWithoutEchantillons(): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.echantillons', 'e')
            ->andWhere('e._id IS NULL')
            ->orderBy('o._id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Statistiques des échantillons par okara
     */
    public function getEchantillonsStats(): array
    {
        $results = $this->createQueryBuilder('o')
            ->select('o._id as okara_id, COUNT(e._id) as nb_echantillons')
            ->leftJoin('o.echantillons', 'e')
            ->groupBy('o._id')
            ->orderBy('nb_echantillons', 'DESC')
            ->getQuery()
            ->getResult();

        $totalOkaras = $this->count([]);
        $totalEchantillons = array_sum(array_column($results, 'nb_echantillons'));
        $okarasSansEchantillons = count($this->findWithoutEchantillons());

        return [
            'total_okaras' => $totalOkaras,
            'total_echantillons' => $totalEchantillons,
            'moyenne_echantillons_par_okara' => $totalOkaras > 0 ? 
                round($totalEchantillons / $totalOkaras, 2) : 0,
            'okaras_sans_echantillons' => $okarasSansEchantillons,
            'taux_okaras_avec_echantillons' => $totalOkaras > 0 ? 
                round((($totalOkaras - $okarasSansEchantillons) / $totalOkaras) * 100, 2) : 0,
            'repartition' => $results
        ];
    }

    /**
     * Trouve les okaras les plus analysés
     */
    public function findMostAnalyzed(int $limit = 10): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.echantillons', 'e')
            ->groupBy('o._id')
            ->orderBy('COUNT(e._id)', 'DESC')
            ->addOrderBy('o._id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les okaras récents avec leurs échantillons
     */
    public function findRecentWithEchantillons(int $limit = 20): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.echantillons', 'e')
            ->addSelect('e')
            ->orderBy('o._id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche avec filtres multiples
     */
    public function searchWithFilters(array $filters = []): array
    {
        $qb = $this->createQueryBuilder('o');

        if (isset($filters['has_echantillons']) && $filters['has_echantillons'] !== '') {
            $qb->leftJoin('o.echantillons', 'e');
            if ($filters['has_echantillons']) {
                $qb->andWhere('e._id IS NOT NULL');
            } else {
                $qb->andWhere('e._id IS NULL');
            }
        }

        if (isset($filters['min_echantillons']) && $filters['min_echantillons'] !== '') {
            $qb->leftJoin('o.echantillons', 'e')
               ->groupBy('o._id')
               ->having('COUNT(e._id) >= :minCount')
               ->setParameter('minCount', (int) $filters['min_echantillons']);
        }

        if (isset($filters['max_echantillons']) && $filters['max_echantillons'] !== '') {
            $qb->leftJoin('o.echantillons', 'e')
               ->groupBy('o._id')
               ->having('COUNT(e._id) <= :maxCount')
               ->setParameter('maxCount', (int) $filters['max_echantillons']);
        }

        return $qb->orderBy('o._id', 'DESC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Données pour tableau de bord okara
     */
    public function getDashboardData(): array
    {
        $stats = $this->getEchantillonsStats();
        $mostAnalyzed = $this->findMostAnalyzed(5);
        $withoutEchantillons = $this->findWithoutEchantillons();
        $recent = $this->findRecentWithEchantillons(10);

        return [
            'statistiques' => $stats,
            'okaras_plus_analyses' => array_map(function ($okara) {
                return [
                    'id' => $okara->getId(),
                    'nb_echantillons' => count($okara->getEchantillons()),
                    'of_numero' => $okara->getOf()?->getNumero()
                ];
            }, $mostAnalyzed),
            'okaras_sans_echantillons' => array_map(function ($okara) {
                return [
                    'id' => $okara->getId(),
                    'of_numero' => $okara->getOf()?->getNumero()
                ];
            }, $withoutEchantillons),
            'okaras_recents' => array_map(function ($okara) {
                return [
                    'id' => $okara->getId(),
                    'nb_echantillons' => count($okara->getEchantillons()),
                    'of_numero' => $okara->getOf()?->getNumero()
                ];
            }, array_slice($recent, 0, 5)),
            'resume' => [
                'total' => $stats['total_okaras'],
                'avec_echantillons' => $stats['total_okaras'] - $stats['okaras_sans_echantillons'],
                'sans_echantillons' => $stats['okaras_sans_echantillons'],
                'moyenne_echantillons' => $stats['moyenne_echantillons_par_okara']
            ]
        ];
    }

    /**
     * Rapport d'activité des okaras
     */
    public function getActivityReport(): array
    {
        $dashboard = $this->getDashboardData();
        $stats = $dashboard['statistiques'];
        
        return [
            'resume_general' => $dashboard['resume'],
            'performance_analyse' => [
                'taux_okaras_analyses' => $stats['taux_okaras_avec_echantillons'],
                'moyenne_echantillons' => $stats['moyenne_echantillons_par_okara'],
                'total_echantillons' => $stats['total_echantillons']
            ],
            'top_analyses' => $dashboard['okaras_plus_analyses'],
            'alertes' => [
                'okaras_non_analyses' => count($dashboard['okaras_sans_echantillons']),
                'details_non_analyses' => $dashboard['okaras_sans_echantillons']
            ],
            'activite_recente' => $dashboard['okaras_recents'],
            'recommandations' => [
                'couverture_analyse' => $stats['taux_okaras_avec_echantillons'] < 80 ? 
                    'Améliorer la couverture d\'analyse des okaras' : 'Couverture d\'analyse satisfaisante',
                'qualite_donnees' => count($dashboard['okaras_sans_echantillons']) > 0 ? 
                    'Analyser les okaras sans échantillons' : 'Tous les okaras sont analysés'
            ]
        ];
    }

    /**
     * Compte le nombre total d'okaras
     */
    public function countTotal(): int
    {
        return $this->count([]);
    }

    /**
     * Trouve les okaras par liste d'IDs
     */
    public function findByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        return $this->createQueryBuilder('o')
            ->andWhere('o._id IN (:ids)')
            ->setParameter('ids', $ids)
            ->orderBy('o._id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
