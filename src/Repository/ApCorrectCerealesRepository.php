<?php

namespace App\Repository;

use App\Entity\ApCorrectCereales;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ApCorrectCerealesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApCorrectCereales::class);
    }
}
