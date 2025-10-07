<?php

namespace App\Repository;

use App\Entity\DecanteurCereales;
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

    // Ajoutez ici vos méthodes personnalisées si besoin
}
