<?php

namespace App\Repository;

use App\Entity\ApCorrectSoja;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApCorrectSoja>
 *
 * @method ApCorrectSoja|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApCorrectSoja|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApCorrectSoja[]    findAll()
 * @method ApCorrectSoja[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApCorrectSojaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApCorrectSoja::class);
    }

    // Ajoutez ici des méthodes personnalisées si nécessaire
}
