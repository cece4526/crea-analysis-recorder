<?php

namespace App\Repository;

use App\Entity\Enzyme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Enzyme>
 *
 * @method Enzyme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enzyme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enzyme[]    findAll()
 * @method Enzyme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnzymeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enzyme::class);
    }

    public function save(Enzyme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Enzyme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Trouve une enzyme par son nom
     */
    public function findByName(string $name): ?Enzyme
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e._name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Recherche d'enzymes par nom partiel
     */
    public function searchByName(string $searchTerm): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e._name LIKE :search')
            ->setParameter('search', '%' . $searchTerm . '%')
            ->orderBy('e._name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve toutes les enzymes triées par nom
     */
    public function findAllOrderedByName(): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e._name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les enzymes avec leurs quantités associées
     */
    public function findWithQuantites(): array
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e._quantiteEnzymes', 'qe')
            ->addSelect('qe')
            ->orderBy('e._name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve une enzyme avec ses quantités
     */
    public function findWithQuantitesById(int $id): ?Enzyme
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e._quantiteEnzymes', 'qe')
            ->addSelect('qe')
            ->andWhere('e._id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Compte le nombre total d'enzymes
     */
    public function countTotal(): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e._id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Trouve les enzymes les plus utilisées
     */
    public function findMostUsed(int $limit = 10): array
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e._quantiteEnzymes', 'qe')
            ->groupBy('e._id')
            ->orderBy('COUNT(qe.id)', 'DESC')
            ->addOrderBy('e._name', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les enzymes non utilisées
     */
    public function findUnused(): array
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e._quantiteEnzymes', 'qe')
            ->andWhere('qe.id IS NULL')
            ->orderBy('e._name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Statistiques d'utilisation des enzymes
     */
    public function getUsageStats(): array
    {
        $totalEnzymes = $this->countTotal();
        $usedEnzymes = count($this->findUsed());
        $unusedEnzymes = count($this->findUnused());

        return [
            'total_enzymes' => $totalEnzymes,
            'enzymes_utilisees' => $usedEnzymes,
            'enzymes_non_utilisees' => $unusedEnzymes,
            'taux_utilisation' => $totalEnzymes > 0 ? round(($usedEnzymes / $totalEnzymes) * 100, 2) : 0
        ];
    }

    /**
     * Trouve les enzymes utilisées (avec au moins une quantité associée)
     */
    public function findUsed(): array
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e._quantiteEnzymes', 'qe')
            ->groupBy('e._id')
            ->orderBy('e._name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche avec filtres multiples
     */
    public function searchWithFilters(array $filters = []): array
    {
        $qb = $this->createQueryBuilder('e');

        if (isset($filters['name']) && !empty($filters['name'])) {
            $qb->andWhere('e._name LIKE :name')
               ->setParameter('name', '%' . $filters['name'] . '%');
        }

        if (isset($filters['has_quantities']) && $filters['has_quantities'] !== '') {
            if ($filters['has_quantities']) {
                $qb->innerJoin('e._quantiteEnzymes', 'qe');
            } else {
                $qb->leftJoin('e._quantiteEnzymes', 'qe')
                   ->andWhere('qe.id IS NULL');
            }
        }

        return $qb->orderBy('e._name', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Données pour tableau de bord des enzymes
     */
    public function getDashboardData(): array
    {
        $stats = $this->getUsageStats();
        $mostUsed = $this->findMostUsed(5);
        $unused = $this->findUnused();

        return [
            'statistiques' => $stats,
            'enzymes_plus_utilisees' => array_map(function ($enzyme) {
                return [
                    'id' => $enzyme->getId(),
                    'name' => $enzyme->getName(),
                    'nb_utilisations' => count($enzyme->getQuantiteEnzymes())
                ];
            }, $mostUsed),
            'enzymes_non_utilisees' => array_map(function ($enzyme) {
                return [
                    'id' => $enzyme->getId(),
                    'name' => $enzyme->getName()
                ];
            }, $unused),
            'resume' => [
                'total' => $stats['total_enzymes'],
                'utilisees' => $stats['enzymes_utilisees'],
                'non_utilisees' => $stats['enzymes_non_utilisees'],
                'taux_utilisation' => $stats['taux_utilisation']
            ]
        ];
    }

    /**
     * Rapport d'activité des enzymes
     */
    public function getActivityReport(): array
    {
        $dashboard = $this->getDashboardData();
        
        return [
            'resume_general' => $dashboard['resume'],
            'top_enzymes' => $dashboard['enzymes_plus_utilisees'],
            'enzymes_inactives' => $dashboard['enzymes_non_utilisees'],
            'recommandations' => [
                'enzymes_a_supprimer' => count($dashboard['enzymes_non_utilisees']) > 0 ? 
                    'Considérer la suppression des enzymes non utilisées' : 'Aucune enzyme inactive',
                'optimisation' => count($dashboard['enzymes_plus_utilisees']) > 0 ? 
                    'Enzymes principales identifiées pour optimisation des stocks' : 'Analyser l\'usage des enzymes'
            ]
        ];
    }

    /**
     * Vérifie si une enzyme existe par nom
     */
    public function existsByName(string $name): bool
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e._id)')
            ->andWhere('e._name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

    /**
     * Trouve les enzymes par liste d'IDs
     */
    public function findByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        return $this->createQueryBuilder('e')
            ->andWhere('e._id IN (:ids)')
            ->setParameter('ids', $ids)
            ->orderBy('e._name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
