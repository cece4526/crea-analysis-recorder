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

    public function save(HeureEnzyme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(HeureEnzyme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Trouve les heures d'enzyme par plage horaire
     */
    public function findByTimeRange(\DateTimeInterface $startTime, \DateTimeInterface $endTime): array
    {
        return $this->createQueryBuilder('he')
            ->andWhere('he.heure BETWEEN :start AND :end')
            ->setParameter('start', $startTime)
            ->setParameter('end', $endTime)
            ->orderBy('he.heure', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Statistiques par heure
     */
    public function getHourlyStats(): array
    {
        $results = $this->createQueryBuilder('he')
            ->select('
                HOUR(he.heure) as heure,
                COUNT(he.id) as nb_operations
            ')
            ->groupBy('heure')
            ->orderBy('heure', 'ASC')
            ->getQuery()
            ->getResult();

        return array_map(function ($result) {
            return [
                'heure' => (int) $result['heure'],
                'nb_operations' => (int) $result['nb_operations']
            ];
        }, $results);
    }

    /**
     * Heures récentes
     */
    public function findRecent(int $hours = 24): array
    {
        $startTime = new \DateTime();
        $startTime->modify("-{$hours} hours");

        return $this->createQueryBuilder('he')
            ->andWhere('he.heure >= :startTime')
            ->setParameter('startTime', $startTime)
            ->orderBy('he.heure', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Données pour tableau de bord heures enzyme
     */
    public function getDashboardData(): array
    {
        $total = $this->count([]);
        $recent = $this->findRecent();
        $hourlyStats = $this->getHourlyStats();

        return [
            'total_enregistrements' => $total,
            'enregistrements_recents' => count($recent),
            'repartition_horaire' => $hourlyStats,
            'resume' => [
                'activite_24h' => count($recent)
            ]
        ];
    }

    /**
     * Rapport d'activité heures enzyme
     */
    public function getActivityReport(): array
    {
        $dashboard = $this->getDashboardData();
        
        return [
            'resume_general' => [
                'total_enregistrements' => $dashboard['total_enregistrements'],
                'activite_recente' => $dashboard['resume']['activite_24h']
            ],
            'analyse_temporelle' => $dashboard['repartition_horaire'],
            'recommandations' => [
                'suivi' => 'Maintenir l\'enregistrement des heures d\'enzyme'
            ]
        ];
    }
}
