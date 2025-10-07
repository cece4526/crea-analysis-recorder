<?php

namespace App\Repository;

use App\Entity\AnalyseSoja;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AnalyseSojaRepository
 *
 * @category Repository
 * @package  App\Repository
 * @author   VotreNom <votre@email.com>
 * @license  MIT
 * @link     https://votreprojet.com
 *
 * @extends ServiceEntityRepository<AnalyseSoja>
 *
 * @method AnalyseSoja|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnalyseSoja|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnalyseSoja[]    findAll()
 * @method AnalyseSoja[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnalyseSojaRepository extends ServiceEntityRepository
{
    /**
     * AnalyseSojaRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnalyseSoja::class);
    }

    // Ajoutez ici vos méthodes personnalisées si besoin
}
