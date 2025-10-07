<?php

namespace App\Repository;

use App\Entity\OF;
use App\Entity\Production;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * Repository pour OF (Ordre de Fabrication) - Entité centrale du système
 * 
 * @extends ServiceEntityRepository<OF>
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

    /**
     * Trouve tous les OFs avec leur production associée
     */
    public function findAllWithProduction(): array
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve un OF par son numéro
     */
    public function findByNumero(int $numero): ?OF
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.numero = :numero')
            ->setParameter('numero', $numero)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Trouve les OFs par production
     */
    public function findByProduction(Production $production): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.production = :production')
            ->setParameter('production', $production)
            ->orderBy('o.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les OFs par nature de produit
     */
    public function findByNature(string $nature): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.nature = :nature')
            ->setParameter('nature', $nature)
            ->orderBy('o.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les OFs dans une plage de dates
     */
    public function findByDateRange(\DateTimeInterface $dateDebut, \DateTimeInterface $dateFin): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.date BETWEEN :dateDebut AND :dateFin')
            ->setParameter('dateDebut', $dateDebut)
            ->setParameter('dateFin', $dateFin)
            ->orderBy('o.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les OFs récents (derniers 30 jours par défaut)
     */
    public function findRecent(int $jours = 30): array
    {
        $dateLimit = new \DateTime("-{$jours} days");
        
        return $this->createQueryBuilder('o')
            ->andWhere('o.date >= :dateLimit')
            ->setParameter('dateLimit', $dateLimit)
            ->orderBy('o.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte les OFs par nature
     */
    public function countByNature(): array
    {
        $result = $this->createQueryBuilder('o')
            ->select('o.nature, COUNT(o.id) as total')
            ->groupBy('o.nature')
            ->orderBy('total', 'DESC')
            ->getQuery()
            ->getResult();

        $stats = [];
        foreach ($result as $row) {
            $stats[$row['nature']] = (int)$row['total'];
        }

        return $stats;
    }

    /**
     * Compte les OFs par mois pour statistiques
     */
    public function countByMonth(int $annee = null): array
    {
        $annee = $annee ?: (int)(new \DateTime())->format('Y');
        
        return $this->createQueryBuilder('o')
            ->select('
                MONTH(o.date) as mois,
                COUNT(o.id) as total,
                o.nature
            ')
            ->andWhere('YEAR(o.date) = :annee')
            ->setParameter('annee', $annee)
            ->groupBy('mois, o.nature')
            ->orderBy('mois', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche OFs par numéro partiel ou nom
     */
    public function searchByNumeroOrName(string $terme): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.numero LIKE :terme OR o.name LIKE :terme')
            ->setParameter('terme', '%' . $terme . '%')
            ->orderBy('o.date', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les OFs avec analyses soja
     */
    public function findWithAnalysesSoja(): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('SIZE(o.analyseSojas) > 0')
            ->orderBy('o.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les OFs sans analyses (à surveiller)
     */
    public function findWithoutAnalyses(): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('SIZE(o.analyseSojas) = 0')
            ->andWhere('o.nature = :soja')
            ->setParameter('soja', 'Soja')
            ->orderBy('o.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche avec filtres multiples
     */
    public function searchWithFilters(array $criteria = []): QueryBuilder
    {
        $qb = $this->createQueryBuilder('o');

        if (!empty($criteria['dateDebut'])) {
            $qb->andWhere('o.date >= :dateDebut')
               ->setParameter('dateDebut', $criteria['dateDebut']);
        }

        if (!empty($criteria['dateFin'])) {
            $qb->andWhere('o.date <= :dateFin')
               ->setParameter('dateFin', $criteria['dateFin']);
        }

        if (!empty($criteria['nature'])) {
            $qb->andWhere('o.nature = :nature')
               ->setParameter('nature', $criteria['nature']);
        }

        if (!empty($criteria['numero'])) {
            $qb->andWhere('o.numero = :numero')
               ->setParameter('numero', $criteria['numero']);
        }

        if (!empty($criteria['name'])) {
            $qb->andWhere('o.name LIKE :name')
               ->setParameter('name', '%' . $criteria['name'] . '%');
        }

        return $qb->orderBy('o.date', 'DESC');
    }

    /**
     * Obtient le tableau de bord des OFs
     */
    public function getDashboardData(int $limitDays = 30): array
    {
        $dateLimit = new \DateTime("-{$limitDays} days");
        
        $ofs = $this->createQueryBuilder('o')
            ->andWhere('o.date >= :dateLimit')
            ->setParameter('dateLimit', $dateLimit)
            ->orderBy('o.date', 'DESC')
            ->getQuery()
            ->getResult();

        $stats = [
            'total' => count($ofs),
            'par_nature' => [],
            'derniers_numeros' => [],
            'moyenne_par_jour' => 0
        ];

        // Statistiques par nature
        foreach ($ofs as $of) {
            $nature = $of->getNature() ?: 'Non défini';
            $stats['par_nature'][$nature] = ($stats['par_nature'][$nature] ?? 0) + 1;
            
            if (count($stats['derniers_numeros']) < 5) {
                $stats['derniers_numeros'][] = $of->getNumero();
            }
        }

        // Moyenne par jour
        if ($limitDays > 0) {
            $stats['moyenne_par_jour'] = round($stats['total'] / $limitDays, 2);
        }

        return $stats;
    }

    /**
     * Trouve les OFs problématiques (anciens sans analyses)
     */
    public function findProblematiques(int $joursLimite = 7): array
    {
        $dateLimit = new \DateTime("-{$joursLimite} days");
        
        return $this->createQueryBuilder('o')
            ->andWhere('o.date < :dateLimit')
            ->andWhere('SIZE(o.analyseSojas) = 0')
            ->andWhere('o.nature = :soja')
            ->setParameter('dateLimit', $dateLimit)
            ->setParameter('soja', 'Soja')
            ->orderBy('o.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Sauvegarde une entité
     */
    public function save(OF $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Supprime une entité
     */
    public function remove(OF $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
