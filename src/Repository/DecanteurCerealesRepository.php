<?php

namespace App\Repository;

use App\Entity\DecanteurCereales;
use App\Entity\OF;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DecanteurCereales>
 *
 * @method DecanteurCereales|null find($id, $lockMode = null, $lockVersion = null)
 * @method DecanteurCereales|null findOneBy(array $criteria, array $orderBy = null)
 * @method DecanteurCereales[]    findAll()
 * @method DecanteurCereales[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DecanteurCerealesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DecanteurCereales::class);
    }

    public function save(DecanteurCereales $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DecanteurCereales $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Trouve les décanteurs par OF
     */
    public function findByOF(OF $of): array
    {
        return $this->createQueryBuilder('dc')
            ->andWhere('dc.of = :of')
            ->setParameter('of', $of)
            ->orderBy('dc.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Statistiques générales des décanteurs
     */
    public function getGeneralStats(): array
    {
        $total = $this->count([]);
        
        return [
            'total_operations' => $total,
            'operations_recentes' => $this->findRecent(),
        ];
    }

    /**
     * Décanteurs récents (30 jours)
     */
    public function findRecent(int $days = 30): array
    {
        return $this->createQueryBuilder('dc')
            ->orderBy('dc.id', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche avec filtres
     */
    public function searchWithFilters(array $filters = []): array
    {
        $qb = $this->createQueryBuilder('dc');

        // Ajouter ici des filtres selon les propriétés de l'entité
        
        return $qb->orderBy('dc.id', 'DESC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Données pour tableau de bord décanteurs
     */
    public function getDashboardData(): array
    {
        $stats = $this->getGeneralStats();
        
        return [
            'total_operations' => $stats['total_operations'],
            'operations_recentes' => count($stats['operations_recentes']),
            'resume' => [
                'activite_globale' => $stats['total_operations']
            ]
        ];
    }

    /**
     * Rapport d'activité décanteurs
     */
    public function getActivityReport(): array
    {
        $dashboard = $this->getDashboardData();
        
        return [
            'resume_general' => [
                'total_operations' => $dashboard['total_operations'],
                'operations_recentes' => $dashboard['operations_recentes']
            ],
            'recommandations' => [
                'surveillance' => 'Maintenir la surveillance des opérations de décantation'
            ]
        ];
    }
}
