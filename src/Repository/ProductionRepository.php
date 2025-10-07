<?php

namespace App\Repository;

use App\Entity\Production;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité Production
 * 
 * Gère l'accès aux données de production avec des méthodes métier spécialisées
 * 
 * @extends ServiceEntityRepository<Production>
 *
 * @method Production|null find($id, $lockMode = null, $lockVersion = null)
 * @method Production|null findOneBy(array $criteria, array $orderBy = null)
 * @method Production[]    findAll()
 * @method Production[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Production::class);
    }

    /**
     * Trouve toutes les productions avec leurs ordres de fabrication (OF)
     * 
     * @return Production[]
     */
    public function findAllWithOF(): array
    {
        // Méthode temporairement simplifiée sans relation
        return $this->createQueryBuilder('p')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les productions par statut
     * 
     * @param string $status Le statut recherché
     * @return Production[]
     */
    public function findByStatus(string $status): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.status = :status')
            ->setParameter('status', $status)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les productions actives (statut différent de 'terminée')
     * 
     * @return Production[]
     */
    public function findActiveProductions(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.status != :terminee OR p.status IS NULL')
            ->setParameter('terminee', 'Terminée')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les productions avec une quantité minimale
     * 
     * @param int $minQuantity Quantité minimale
     * @return Production[]
     */
    public function findByMinQuantity(int $minQuantity): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.quantity >= :minQuantity')
            ->setParameter('minQuantity', $minQuantity)
            ->orderBy('p.quantity', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche productions par nom (recherche partielle)
     * 
     * @param string $searchTerm Terme de recherche
     * @return Production[]
     */
    public function searchByName(string $searchTerm): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.name LIKE :search')
            ->setParameter('search', '%' . $searchTerm . '%')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte le nombre de productions par statut
     * 
     * @return array Tableau associatif [statut => nombre]
     */
    public function countByStatus(): array
    {
        $result = $this->createQueryBuilder('p')
            ->select('p.status as status, COUNT(p.id) as count')
            ->groupBy('p.status')
            ->getQuery()
            ->getResult();

        $statusCount = [];
        foreach ($result as $row) {
            $statusCount[$row['status'] ?? 'Non défini'] = (int) $row['count'];
        }

        return $statusCount;
    }

    /**
     * Trouve les productions avec le plus d'ordres de fabrication
     * 
     * @param int $limit Nombre de résultats max
     * @return Production[]
     */
    public function findTopProductionsByOFCount(int $limit = 10): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.ofs', 'o')
            ->addSelect('COUNT(o.id) as HIDDEN ofCount')
            ->groupBy('p.id')
            ->orderBy('ofCount', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Calcule la quantité totale produite
     * 
     * @return int Somme des quantités
     */
    public function getTotalQuantity(): int
    {
        $result = $this->createQueryBuilder('p')
            ->select('SUM(p.quantity) as total')
            ->getQuery()
            ->getSingleScalarResult();

        return (int) ($result ?? 0);
    }

    /**
     * Trouve une production avec tous ses OF et relations
     * 
     * @param int $id ID de la production
     * @return Production|null
     */
    public function findOneWithFullRelations(int $id): ?Production
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.ofs', 'o')
            ->addSelect('o')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * QueryBuilder de base pour les requêtes personnalisées
     * 
     * @param string $alias Alias de la table
     * @return QueryBuilder
     */
    public function createBaseQueryBuilder(string $alias = 'p'): QueryBuilder
    {
        return $this->createQueryBuilder($alias)
            ->orderBy($alias . '.name', 'ASC');
    }

    /**
     * Sauvegarde une production en base
     * 
     * @param Production $production Production à sauvegarder
     * @param bool $flush Exécuter la sauvegarde immédiatement
     */
    public function save(Production $production, bool $flush = false): void
    {
        $this->getEntityManager()->persist($production);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Supprime une production de la base
     * 
     * @param Production $production Production à supprimer
     * @param bool $flush Exécuter la suppression immédiatement
     */
    public function remove(Production $production, bool $flush = false): void
    {
        $this->getEntityManager()->remove($production);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
