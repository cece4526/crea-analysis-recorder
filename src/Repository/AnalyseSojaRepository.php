<?php

namespace App\Repository;

use App\Entity\AnalyseSoja;
use App\Entity\OF;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * Repository pour AnalyseSoja - Gestion des analyses qualité du soja
 * 
 * @extends ServiceEntityRepository<AnalyseSoja>
 * @method AnalyseSoja|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnalyseSoja|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnalyseSoja[]    findAll()
 * @method AnalyseSoja[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnalyseSojaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnalyseSoja::class);
    }

    /**
     * Trouve toutes les analyses avec leur OF associé
     */
    public function findAllWithOF(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les analyses par OF
     */
    public function findByOF(OF $of): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.of = :of')
            ->setParameter('of', $of)
            ->orderBy('a.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les analyses par numéro d'OF
     */
    public function findByOFNumero(int $numeroOF): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.of', 'o')
            ->andWhere('o.numero = :numero')
            ->setParameter('numero', $numeroOF)
            ->orderBy('a.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les analyses dans une plage de dates
     */
    public function findByDateRange(\DateTimeInterface $dateDebut, \DateTimeInterface $dateFin): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.of', 'o')
            ->addSelect('o')
            ->andWhere('a.date BETWEEN :dateDebut AND :dateFin')
            ->setParameter('dateDebut', $dateDebut)
            ->setParameter('dateFin', $dateFin)
            ->orderBy('a.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les analyses avec contrôle visuel réussi ou échoué
     */
    public function findByControlVisuel(bool $controlReussi): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.controlVisuel = :control')
            ->setParameter('control', $controlReussi)
            ->orderBy('a.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les analyses par pilote
     */
    public function findByPilote(string $initialPilote): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.initialPilote = :pilote')
            ->setParameter('pilote', $initialPilote)
            ->orderBy('a.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte les analyses par statut de contrôle visuel
     */
    public function countByControlVisuel(): array
    {
        $result = $this->createQueryBuilder('a')
            ->select('a.controlVisuel, COUNT(a.id) as total')
            ->groupBy('a.controlVisuel')
            ->getQuery()
            ->getResult();

        $stats = ['reussi' => 0, 'echec' => 0, 'null' => 0];
        foreach ($result as $row) {
            if ($row['controlVisuel'] === true) {
                $stats['reussi'] = (int)$row['total'];
            } elseif ($row['controlVisuel'] === false) {
                $stats['echec'] = (int)$row['total'];
            } else {
                $stats['null'] = (int)$row['total'];
            }
        }

        return $stats;
    }

    /**
     * Obtient les statistiques de températures de broyage
     */
    public function getTemperatureStats(): array
    {
        return $this->createQueryBuilder('a')
            ->select('
                AVG(a.temperatureBroyage) as moyenne,
                MIN(a.temperatureBroyage) as minimum,
                MAX(a.temperatureBroyage) as maximum,
                COUNT(a.id) as total
            ')
            ->andWhere('a.temperatureBroyage IS NOT NULL')
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * Obtient les statistiques d'ES (Extrait Sec)
     */
    public function getESStats(): array
    {
        return $this->createQueryBuilder('a')
            ->select('
                AVG(a.esAvDecan) as moyenneAvant,
                AVG(a.esApDecan) as moyenneApres,
                MIN(a.esAvDecan) as minAvant,
                MAX(a.esAvDecan) as maxAvant,
                MIN(a.esApDecan) as minApres,
                MAX(a.esApDecan) as maxApres,
                COUNT(a.id) as total
            ')
            ->andWhere('a.esAvDecan IS NOT NULL OR a.esApDecan IS NOT NULL')
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * Recherche par critères multiples avec filtre avancé
     */
    public function searchWithFilters(array $criteria = []): QueryBuilder
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.of', 'o')
            ->addSelect('o');

        if (!empty($criteria['dateDebut'])) {
            $qb->andWhere('a.date >= :dateDebut')
               ->setParameter('dateDebut', $criteria['dateDebut']);
        }

        if (!empty($criteria['dateFin'])) {
            $qb->andWhere('a.date <= :dateFin')
               ->setParameter('dateFin', $criteria['dateFin']);
        }

        if (!empty($criteria['numeroOF'])) {
            $qb->andWhere('o.numero = :numeroOF')
               ->setParameter('numeroOF', $criteria['numeroOF']);
        }

        if (!empty($criteria['pilote'])) {
            $qb->andWhere('a.initialPilote = :pilote')
               ->setParameter('pilote', $criteria['pilote']);
        }

        if (isset($criteria['controlVisuel'])) {
            $qb->andWhere('a.controlVisuel = :control')
               ->setParameter('control', $criteria['controlVisuel']);
        }

        if (!empty($criteria['litrageMin'])) {
            $qb->andWhere('a.litrageDecan >= :litrageMin')
               ->setParameter('litrageMin', $criteria['litrageMin']);
        }

        if (!empty($criteria['litrageMax'])) {
            $qb->andWhere('a.litrageDecan <= :litrageMax')
               ->setParameter('litrageMax', $criteria['litrageMax']);
        }

        return $qb->orderBy('a.date', 'DESC');
    }

    /**
     * Obtient le tableau de bord des analyses récentes
     */
    public function getDashboardData(int $limitDays = 30): array
    {
        $dateLimit = new \DateTime("-{$limitDays} days");
        
        $analyses = $this->createQueryBuilder('a')
            ->leftJoin('a.of', 'o')
            ->addSelect('o')
            ->andWhere('a.date >= :dateLimit')
            ->setParameter('dateLimit', $dateLimit)
            ->orderBy('a.date', 'DESC')
            ->getQuery()
            ->getResult();

        $stats = [
            'total' => count($analyses),
            'controle_visuel_ok' => 0,
            'controle_visuel_ko' => 0,
            'temperature_moyenne' => 0,
            'litrage_total' => 0,
            'pilotes' => []
        ];

        $temperatures = [];
        foreach ($analyses as $analyse) {
            if ($analyse->isControlVisuel() === true) {
                $stats['controle_visuel_ok']++;
            } elseif ($analyse->isControlVisuel() === false) {
                $stats['controle_visuel_ko']++;
            }

            if ($analyse->getTemperatureBroyage()) {
                $temperatures[] = (float)$analyse->getTemperatureBroyage();
            }

            if ($analyse->getLitrageDecan()) {
                $stats['litrage_total'] += $analyse->getLitrageDecan();
            }

            if ($analyse->getInitialPilote()) {
                $pilote = $analyse->getInitialPilote();
                $stats['pilotes'][$pilote] = ($stats['pilotes'][$pilote] ?? 0) + 1;
            }
        }

        if (!empty($temperatures)) {
            $stats['temperature_moyenne'] = array_sum($temperatures) / count($temperatures);
        }

        return $stats;
    }

    /**
     * Trouve les analyses anormales (critères de qualité non respectés)
     */
    public function findAnalysesAnormales(): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.of', 'o')
            ->addSelect('o')
            ->andWhere('
                a.controlVisuel = false 
                OR (a.temperatureBroyage IS NOT NULL AND CAST(a.temperatureBroyage AS DECIMAL(10,2)) > 80)
                OR (a.esAvDecan IS NOT NULL AND CAST(a.esAvDecan AS DECIMAL(10,2)) < 10)
                OR (a.esApDecan IS NOT NULL AND CAST(a.esApDecan AS DECIMAL(10,2)) < 15)
            ')
            ->orderBy('a.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Sauvegarde une entité
     */
    public function save(AnalyseSoja $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Supprime une entité
     */
    public function remove(AnalyseSoja $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
