<?php

namespace App\Repository;

use App\Entity\AvCorrectCereales;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AvCorrectCerealesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvCorrectCereales::class);
    }
}
