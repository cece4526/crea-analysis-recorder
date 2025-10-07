<?php

namespace App\Repository;

use App\Entity\QuantiteEnzyme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuantiteEnzyme>
 *
 * @method QuantiteEnzyme|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuantiteEnzyme|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuantiteEnzyme[]    findAll()
 * @method QuantiteEnzyme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuantiteEnzymeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuantiteEnzyme::class);
    }

    // Ajoutez ici vos méthodes personnalisées si besoin
}
