<?php

namespace App\Repository;

use App\Entity\HACCP;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HACCP>
 *
 * @method HACCP|null find($id, $lockMode = null, $lockVersion = null)
 * @method HACCP|null findOneBy(array $criteria, array $orderBy = null)
 * @method HACCP[]    findAll()
 * @method HACCP[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HACCPRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HACCP::class);
    }

    // Ajoutez ici vos méthodes personnalisées si besoin
}
