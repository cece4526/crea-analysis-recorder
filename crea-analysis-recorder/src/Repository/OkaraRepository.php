<?php

namespace App\Repository;

use App\Entity\Okara;
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

    // Ajoutez ici vos méthodes personnalisées si besoin
}
