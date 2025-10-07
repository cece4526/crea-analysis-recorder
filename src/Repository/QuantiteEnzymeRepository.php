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

    public function save(QuantiteEnzyme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(QuantiteEnzyme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Trouve les quantités par type d'enzyme
     */
    public function findByEnzyme($enzyme): array
    {
        return $this->createQueryBuilder('qe')
            ->andWhere('qe.enzyme = :enzyme')
            ->setParameter('enzyme', $enzyme)
            ->orderBy('qe.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Statistiques des quantités d'enzymes
     */
    public function getQuantityStats(): array
    {
        $result = $this->createQueryBuilder('qe')
            ->select('
                AVG(qe.quantite) as quantite_moyenne,
                MIN(qe.quantite) as quantite_min,
                MAX(qe.quantite) as quantite_max,
                SUM(qe.quantite) as quantite_totale,
                COUNT(qe.id) as nb_mesures
            ')
            ->getQuery()
            ->getSingleResult();

        return [
            'quantite_moyenne' => round((float) $result['quantite_moyenne'], 2),
            'quantite_min' => round((float) $result['quantite_min'], 2),
            'quantite_max' => round((float) $result['quantite_max'], 2),
            'quantite_totale' => round((float) $result['quantite_totale'], 2),
            'nb_mesures' => (int) $result['nb_mesures']
        ];
    }

    /**
     * Quantités récentes
     */
    public function findRecent(int $limit = 20): array
    {
        return $this->createQueryBuilder('qe')
            ->orderBy('qe.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche avec filtres
     */
    public function searchWithFilters(array $filters = []): array
    {
        $qb = $this->createQueryBuilder('qe');

        if (isset($filters['quantite_min']) && $filters['quantite_min'] !== '') {
            $qb->andWhere('qe.quantite >= :quantiteMin')
               ->setParameter('quantiteMin', (float) $filters['quantite_min']);
        }

        if (isset($filters['quantite_max']) && $filters['quantite_max'] !== '') {
            $qb->andWhere('qe.quantite <= :quantiteMax')
               ->setParameter('quantiteMax', (float) $filters['quantite_max']);
        }

        return $qb->orderBy('qe.id', 'DESC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Données pour tableau de bord quantités enzyme
     */
    public function getDashboardData(): array
    {
        $total = $this->count([]);
        $stats = $this->getQuantityStats();
        $recent = $this->findRecent(10);

        return [
            'total_enregistrements' => $total,
            'statistiques_quantites' => $stats,
            'enregistrements_recents' => count($recent),
            'resume' => [
                'consommation_totale' => $stats['quantite_totale'],
                'consommation_moyenne' => $stats['quantite_moyenne']
            ]
        ];
    }

    /**
     * Rapport d'activité quantités enzyme
     */
    public function getActivityReport(): array
    {
        $dashboard = $this->getDashboardData();
        
        return [
            'resume_general' => [
                'total_enregistrements' => $dashboard['total_enregistrements'],
                'consommation_totale' => $dashboard['resume']['consommation_totale']
            ],
            'analyse_consommation' => $dashboard['statistiques_quantites'],
            'recommandations' => [
                'optimisation' => $dashboard['resume']['consommation_moyenne'] > 0 ? 
                    'Surveiller la consommation d\'enzymes' : 'Analyser les quantités d\'enzymes utilisées'
            ]
        ];
    }
}
