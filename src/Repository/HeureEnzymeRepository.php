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
     * Trouve les hydrolyses enzymatiques par OF
     */
    public function findByOF(int $ofId): array
    {
        return $this->createQueryBuilder('he')
            ->andWhere('he.of = :ofId')
            ->setParameter('ofId', $ofId)
            ->orderBy('he.heureDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les hydrolyses enzymatiques par OF ordonnées par temps
     */
    public function findByOFOrderedByTime(int $ofId): array
    {
        return $this->createQueryBuilder('he')
            ->andWhere('he.of = :ofId')
            ->setParameter('ofId', $ofId)
            ->orderBy('he.heureDebut', 'ASC')
            ->addOrderBy('he.heureFin', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les hydrolyses actives (non terminées)
     */
    public function findActiveHydrolyses(int $ofId): array
    {
        return $this->createQueryBuilder('he')
            ->andWhere('he.of = :ofId')
            ->andWhere('he.heureFin IS NULL')
            ->setParameter('ofId', $ofId)
            ->orderBy('he.heureDebut', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Calcule la durée moyenne d'hydrolyse pour un OF
     */
    public function getAverageHydrolysisDuration(int $ofId): ?float
    {
        $result = $this->createQueryBuilder('he')
            ->select('AVG(he.dureeHydrolyse) as moyenne_duree')
            ->andWhere('he.of = :ofId')
            ->andWhere('he.dureeHydrolyse IS NOT NULL')
            ->setParameter('ofId', $ofId)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ? (float) $result : null;
    }

    /**
     * Calcule l'efficacité moyenne d'hydrolyse pour un OF
     */
    public function getAverageHydrolysisEfficiency(int $ofId): ?float
    {
        $result = $this->createQueryBuilder('he')
            ->select('AVG(he.efficaciteHydrolyse) as moyenne_efficacite')
            ->andWhere('he.of = :ofId')
            ->andWhere('he.efficaciteHydrolyse IS NOT NULL')
            ->setParameter('ofId', $ofId)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ? (float) $result : null;
    }

    /**
     * Trouve les hydrolyses par type d'enzyme
     */
    public function findByEnzymeType(string $typeEnzyme): array
    {
        return $this->createQueryBuilder('he')
            ->andWhere('he.typeEnzyme = :typeEnzyme')
            ->setParameter('typeEnzyme', $typeEnzyme)
            ->orderBy('he.heureDebut', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Vérifie la conformité globale des hydrolyses pour un OF
     */
    public function checkGlobalConformity(int $ofId): bool
    {
        $nonConformes = $this->createQueryBuilder('he')
            ->select('COUNT(he.id)')
            ->andWhere('he.of = :ofId')
            ->andWhere('he.conformite = false')
            ->setParameter('ofId', $ofId)
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $nonConformes === 0;
    }

    /**
     * Statistiques des hydrolyses pour un OF
     */
    public function getOFHydrolysisStats(int $ofId): array
    {
        $hydrolyses = $this->findByOF($ofId);
        
        if (empty($hydrolyses)) {
            return [
                'total_hydrolyses' => 0,
                'hydrolyses_conformes' => 0,
                'duree_moyenne' => null,
                'efficacite_moyenne' => null,
                'types_enzymes' => []
            ];
        }

        $conformes = array_filter($hydrolyses, fn($h) => $h->getConformite());
        $durees = array_filter(array_map(fn($h) => $h->getDureeHydrolyse(), $hydrolyses));
        $efficacites = array_filter(array_map(fn($h) => $h->getEfficaciteHydrolyse(), $hydrolyses));
        $typesEnzymes = array_unique(array_map(fn($h) => $h->getTypeEnzyme(), $hydrolyses));

        return [
            'total_hydrolyses' => count($hydrolyses),
            'hydrolyses_conformes' => count($conformes),
            'duree_moyenne' => !empty($durees) ? round(array_sum($durees) / count($durees), 1) : null,
            'efficacite_moyenne' => !empty($efficacites) ? round(array_sum($efficacites) / count($efficacites), 2) : null,
            'types_enzymes' => array_values($typesEnzymes),
            'taux_conformite' => round((count($conformes) / count($hydrolyses)) * 100, 1)
        ];
    }

    /**
     * Rapport d'activité enzymatique
     */
    public function getEnzymaticActivityReport(int $ofId): array
    {
        $stats = $this->getOFHydrolysisStats($ofId);
        $hydrolyses = $this->findByOFOrderedByTime($ofId);
        
        return [
            'resume_general' => $stats,
            'detail_hydrolyses' => array_map(function ($hydrolyse) {
                return [
                    'id' => $hydrolyse->getId(),
                    'type_enzyme' => $hydrolyse->getTypeEnzyme(),
                    'heure_debut' => $hydrolyse->getHeureDebut()?->format('Y-m-d H:i:s'),
                    'heure_fin' => $hydrolyse->getHeureFin()?->format('Y-m-d H:i:s'),
                    'duree_effective' => $hydrolyse->getDureeEffective(),
                    'efficacite' => $hydrolyse->getEfficaciteHydrolyse(),
                    'conformite' => $hydrolyse->getConformite(),
                    'termine' => $hydrolyse->isTerminee()
                ];
            }, $hydrolyses),
            'recommandations' => $this->generateRecommendations($stats)
        ];
    }

    /**
     * Génère des recommandations basées sur les statistiques
     */
    private function generateRecommendations(array $stats): array
    {
        $recommendations = [];
        
        if ($stats['taux_conformite'] < 95) {
            $recommendations[] = 'Améliorer les paramètres d\'hydrolyse pour augmenter la conformité';
        }
        
        if ($stats['duree_moyenne'] && $stats['duree_moyenne'] > 120) {
            $recommendations[] = 'Optimiser la durée d\'hydrolyse (actuellement > 2h)';
        }
        
        if ($stats['efficacite_moyenne'] && $stats['efficacite_moyenne'] < 80) {
            $recommendations[] = 'Vérifier la qualité et quantité d\'enzymes utilisées';
        }
        
        if (empty($recommendations)) {
            $recommendations[] = 'Processus d\'hydrolyse optimisé, maintenir les bonnes pratiques';
        }
        
        return $recommendations;
    }
}
