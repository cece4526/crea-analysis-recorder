<?php

namespace App\Repository;

use App\Entity\HACCP;
use App\Entity\OF;
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

    public function save(HACCP $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(HACCP $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Trouve un contrôle HACCP par OF ID
     */
    public function findByOFId(int $ofId): ?HACCP
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.of_id = :ofId')
            ->setParameter('ofId', $ofId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Trouve les contrôles HACCP avec filtres pasteurisateur défaillants
     */
    public function findFiltresPasteurisateurDefaillants(): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.filtre_pasteurisateur_resultat = :resultat')
            ->setParameter('resultat', false)
            ->orderBy('h.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les contrôles HACCP avec filtres NEP défaillants
     */
    public function findFiltresNEPDefaillants(): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.filtre_nep_resultat = :resultat')
            ->setParameter('resultat', false)
            ->orderBy('h.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les contrôles avec écarts de température
     */
    public function findEcartsTemperature(int $ecartMaximum = 5): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('ABS(h.temperature_cible - h.temperature_indique) > :ecart')
            ->setParameter('ecart', $ecartMaximum)
            ->orderBy('h.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte les contrôles par résultat de filtre pasteurisateur
     */
    public function countByFiltrePasteurisateur(): array
    {
        $results = $this->createQueryBuilder('h')
            ->select('h.filtre_pasteurisateur_resultat as resultat, COUNT(h.id) as count')
            ->groupBy('h.filtre_pasteurisateur_resultat')
            ->getQuery()
            ->getResult();

        $counts = ['conforme' => 0, 'non_conforme' => 0, 'non_teste' => 0];
        
        foreach ($results as $result) {
            if ($result['resultat'] === true) {
                $counts['conforme'] = (int) $result['count'];
            } elseif ($result['resultat'] === false) {
                $counts['non_conforme'] = (int) $result['count'];
            } else {
                $counts['non_teste'] = (int) $result['count'];
            }
        }

        return $counts;
    }

    /**
     * Compte les contrôles par résultat de filtre NEP
     */
    public function countByFiltreNEP(): array
    {
        $results = $this->createQueryBuilder('h')
            ->select('h.filtre_nep_resultat as resultat, COUNT(h.id) as count')
            ->groupBy('h.filtre_nep_resultat')
            ->getQuery()
            ->getResult();

        $counts = ['conforme' => 0, 'non_conforme' => 0, 'non_teste' => 0];
        
        foreach ($results as $result) {
            if ($result['resultat'] === true) {
                $counts['conforme'] = (int) $result['count'];
            } elseif ($result['resultat'] === false) {
                $counts['non_conforme'] = (int) $result['count'];
            } else {
                $counts['non_teste'] = (int) $result['count'];
            }
        }

        return $counts;
    }

    /**
     * Statistiques des températures
     */
    public function getTemperatureStats(): array
    {
        $result = $this->createQueryBuilder('h')
            ->select('
                AVG(h.temperature_cible) as temp_cible_moyenne,
                AVG(h.temperature_indique) as temp_indique_moyenne,
                MIN(h.temperature_cible) as temp_cible_min,
                MAX(h.temperature_cible) as temp_cible_max,
                MIN(h.temperature_indique) as temp_indique_min,
                MAX(h.temperature_indique) as temp_indique_max,
                AVG(ABS(h.temperature_cible - h.temperature_indique)) as ecart_moyen
            ')
            ->getQuery()
            ->getSingleResult();

        return [
            'temperature_cible' => [
                'moyenne' => round((float) $result['temp_cible_moyenne'], 2),
                'min' => (int) $result['temp_cible_min'],
                'max' => (int) $result['temp_cible_max']
            ],
            'temperature_indique' => [
                'moyenne' => round((float) $result['temp_indique_moyenne'], 2),
                'min' => (int) $result['temp_indique_min'],
                'max' => (int) $result['temp_indique_max']
            ],
            'ecart_moyen' => round((float) $result['ecart_moyen'], 2)
        ];
    }

    /**
     * Contrôles conformes (tous les critères OK)
     */
    public function findControlesConformes(): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.filtre_pasteurisateur_resultat = :true')
            ->andWhere('h.filtre_nep_resultat = :true')
            ->andWhere('ABS(h.temperature_cible - h.temperature_indique) <= :ecart')
            ->setParameter('true', true)
            ->setParameter('ecart', 5)
            ->orderBy('h.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Contrôles non conformes (au moins un critère défaillant)
     */
    public function findControlesNonConformes(): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('
                h.filtre_pasteurisateur_resultat = :false 
                OR h.filtre_nep_resultat = :false 
                OR ABS(h.temperature_cible - h.temperature_indique) > :ecart
            ')
            ->setParameter('false', false)
            ->setParameter('ecart', 5)
            ->orderBy('h.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche avec filtres multiples
     */
    public function searchWithFilters(array $filters = []): array
    {
        $qb = $this->createQueryBuilder('h');

        if (isset($filters['filtre_pasteurisateur']) && $filters['filtre_pasteurisateur'] !== '') {
            $qb->andWhere('h.filtre_pasteurisateur_resultat = :filtre_pasteurisateur')
               ->setParameter('filtre_pasteurisateur', (bool) $filters['filtre_pasteurisateur']);
        }

        if (isset($filters['filtre_nep']) && $filters['filtre_nep'] !== '') {
            $qb->andWhere('h.filtre_nep_resultat = :filtre_nep')
               ->setParameter('filtre_nep', (bool) $filters['filtre_nep']);
        }

        if (isset($filters['temperature_cible_min']) && $filters['temperature_cible_min'] !== '') {
            $qb->andWhere('h.temperature_cible >= :temp_cible_min')
               ->setParameter('temp_cible_min', (int) $filters['temperature_cible_min']);
        }

        if (isset($filters['temperature_cible_max']) && $filters['temperature_cible_max'] !== '') {
            $qb->andWhere('h.temperature_cible <= :temp_cible_max')
               ->setParameter('temp_cible_max', (int) $filters['temperature_cible_max']);
        }

        if (isset($filters['ecart_maximum']) && $filters['ecart_maximum'] !== '') {
            $qb->andWhere('ABS(h.temperature_cible - h.temperature_indique) <= :ecart_max')
               ->setParameter('ecart_max', (int) $filters['ecart_maximum']);
        }

        if (isset($filters['initialProduction']) && !empty($filters['initialProduction'])) {
            $qb->andWhere('h.initialProduction = :initialProduction')
               ->setParameter('initialProduction', $filters['initialProduction']);
        }

        if (isset($filters['initialNEP']) && !empty($filters['initialNEP'])) {
            $qb->andWhere('h.initialNEP = :initialNEP')
               ->setParameter('initialNEP', $filters['initialNEP']);
        }

        if (isset($filters['initialTEMP']) && !empty($filters['initialTEMP'])) {
            $qb->andWhere('h.initialTEMP = :initialTEMP')
               ->setParameter('initialTEMP', $filters['initialTEMP']);
        }

        return $qb->orderBy('h.id', 'DESC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Données pour tableau de bord HACCP
     */
    public function getDashboardData(): array
    {
        $totalControles = $this->count([]);
        $controlesConformes = count($this->findControlesConformes());
        $controlesNonConformes = count($this->findControlesNonConformes());
        $filtresPasteurisateur = $this->countByFiltrePasteurisateur();
        $filtresNEP = $this->countByFiltreNEP();
        
        // Vérifier s'il y a des données avant de calculer les stats de température
        $temperatureStats = [];
        if ($totalControles > 0) {
            try {
                $temperatureStats = $this->getTemperatureStats();
            } catch (\Exception $e) {
                $temperatureStats = [
                    'temperature_cible' => ['moyenne' => 0, 'min' => 0, 'max' => 0],
                    'temperature_indique' => ['moyenne' => 0, 'min' => 0, 'max' => 0],
                    'ecart_moyen' => 0
                ];
            }
        }

        return [
            'total_controles' => $totalControles,
            'controles_conformes' => $controlesConformes,
            'controles_non_conformes' => $controlesNonConformes,
            'taux_conformite' => $totalControles > 0 ? round(($controlesConformes / $totalControles) * 100, 2) : 0,
            'filtres_pasteurisateur' => $filtresPasteurisateur,
            'filtres_nep' => $filtresNEP,
            'temperature_stats' => $temperatureStats,
            'alertes' => [
                'filtres_pasteurisateur_defaillants' => $filtresPasteurisateur['non_conforme'],
                'filtres_nep_defaillants' => $filtresNEP['non_conforme'],
                'ecarts_temperature' => count($this->findEcartsTemperature())
            ]
        ];
    }

    /**
     * Rapport de conformité HACCP
     */
    public function getConformityReport(): array
    {
        $dashboard = $this->getDashboardData();
        
        return [
            'resume' => [
                'total_controles' => $dashboard['total_controles'],
                'taux_conformite_global' => $dashboard['taux_conformite'],
                'controles_conformes' => $dashboard['controles_conformes'],
                'controles_non_conformes' => $dashboard['controles_non_conformes']
            ],
            'details_conformite' => [
                'filtres_pasteurisateur' => [
                    'conformes' => $dashboard['filtres_pasteurisateur']['conforme'],
                    'non_conformes' => $dashboard['filtres_pasteurisateur']['non_conforme'],
                    'taux_conformite' => $dashboard['total_controles'] > 0 ? 
                        round(($dashboard['filtres_pasteurisateur']['conforme'] / $dashboard['total_controles']) * 100, 2) : 0
                ],
                'filtres_nep' => [
                    'conformes' => $dashboard['filtres_nep']['conforme'],
                    'non_conformes' => $dashboard['filtres_nep']['non_conforme'],
                    'taux_conformite' => $dashboard['total_controles'] > 0 ? 
                        round(($dashboard['filtres_nep']['conforme'] / $dashboard['total_controles']) * 100, 2) : 0
                ],
                'temperatures' => $dashboard['temperature_stats']
            ],
            'points_critiques' => [
                'filtres_pasteurisateur_defaillants' => $this->findFiltresPasteurisateurDefaillants(),
                'filtres_nep_defaillants' => $this->findFiltresNEPDefaillants(),
                'ecarts_temperature_importants' => $this->findEcartsTemperature()
            ]
        ];
    }
}
