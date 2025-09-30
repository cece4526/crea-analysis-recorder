<?php

namespace App\Repository;

use App\Entity\HeureEnzyme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HeureEnzyme>
 *
 * @method HeureEnzyme|null find($id, $lockMode = null, $lockVersion = null)
 * @method HeureEnzyme|null findOneBy(array $criteria, array $orderBy = null)
 * @method HeureEnzyme[]    findAll()
 * @method HeureEnzyme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeureEnzymeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HeureEnzyme::class);
    }

    // Ajoutez ici vos méthodes personnalisées si besoin
}
