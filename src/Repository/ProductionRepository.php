<?php

namespace App\Repository;

use App\Entity\Production;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
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

    // Ajoutez ici vos méthodes personnalisées si besoin
}