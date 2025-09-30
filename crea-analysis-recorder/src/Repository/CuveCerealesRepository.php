<?php

namespace App\Repository;

use App\Entity\CuveCereales;
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

    // Ajoutez ici vos méthodes personnalisées si besoin
}
