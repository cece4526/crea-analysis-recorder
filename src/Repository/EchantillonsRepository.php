<?php

namespace App\Repository;

use App\Entity\Echantillons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Echantillons>
 *
 * @method Echantillons|null find($id, $lockMode = null, $lockVersion = null)
 * @method Echantillons|null findOneBy(array $criteria, array $orderBy = null)
 * @method Echantillons[]    findAll()
 * @method Echantillons[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EchantillonsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Echantillons::class);
    }

    // Ajoutez ici vos méthodes personnalisées si besoin
}
