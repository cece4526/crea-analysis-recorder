<?php

namespace App\Repository;

use App\Entity\OF;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OF>
 *
 * @method OF|null find($id, $lockMode = null, $lockVersion = null)
 * @method OF|null findOneBy(array $criteria, array $orderBy = null)
 * @method OF[]    findAll()
 * @method OF[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OFRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OF::class);
    }

    // Ajoutez ici vos méthodes personnalisées si besoin
}
