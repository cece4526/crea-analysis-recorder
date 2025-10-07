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

    // Ajoutez ici vos méthodes personnalisées si besoin
}
