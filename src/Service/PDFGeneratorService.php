<?php

namespace App\Service;

use App\Entity\OF;
use App\Entity\Production;
use App\Repository\AnalyseSojaRepository;
use App\Repository\AvCorrectSojaRepository;
use App\Repository\ApCorrectSojaRepository;
use App\Repository\OkaraRepository;
use App\Repository\HACCPRepository;
use App\Repository\CuveCerealesRepository;
use App\Repository\HeureEnzymeRepository;
use App\Repository\DecanteurCerealesRepository;
use App\Repository\AvCorrectCerealesRepository;
use App\Repository\ApCorrectCerealesRepository;
use Twig\Environment;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Service de génération de rapports PDF
 * Génère les rapports d'analyses sous forme de tableaux PDF
 */
class PDFGeneratorService
{
    public function __construct(
        private Environment $twig,
        private KernelInterface $kernel,
        private AnalyseSojaRepository $analyseSojaRepository,
        private AvCorrectSojaRepository $avCorrectSojaRepository,
        private ApCorrectSojaRepository $apCorrectSojaRepository,
        private OkaraRepository $okaraRepository,
        private HACCPRepository $haccpRepository,
        private CuveCerealesRepository $cuveCerealesRepository,
        private HeureEnzymeRepository $heureEnzymeRepository,
        private DecanteurCerealesRepository $decanteurCerealesRepository,
        private AvCorrectCerealesRepository $avCorrectCerealesRepository,
        private ApCorrectCerealesRepository $apCorrectCerealesRepository
    ) {
    }

    /**
     * Génère le rapport PDF pour une production SOJA
     */
    public function generateSojaReport(OF $of): string
    {
        $reportData = $this->getSojaReportData($of);
        
        // Générer le HTML du rapport
        $html = $this->twig->render('pdf/soja_report.html.twig', [
            'of' => $of,
            'production' => $of->getProduction(),
            'data' => $reportData,
            'generated_at' => new \DateTime()
        ]);

        return $this->convertHtmlToPdf($html);
    }

    /**
     * Génère le rapport PDF pour une production CÉRÉALES
     */
    public function generateCerealesReport(OF $of): string
    {
        $reportData = $this->getCerealesReportData($of);
        
        // Générer le HTML du rapport
        $html = $this->twig->render('pdf/cereales_report.html.twig', [
            'of' => $of,
            'production' => $of->getProduction(),
            'data' => $reportData,
            'generated_at' => new \DateTime()
        ]);

        return $this->convertHtmlToPdf($html);
    }

    /**
     * Génère un rapport global de production
     */
    public function generateGlobalProductionReport(Production $production, array $ofs): string
    {
        $globalData = [];
        
        foreach ($ofs as $of) {
            if ($production->getType() === 'soja') {
                $globalData[] = [
                    'of' => $of,
                    'data' => $this->getSojaReportData($of)
                ];
            } else {
                $globalData[] = [
                    'of' => $of,
                    'data' => $this->getCerealesReportData($of)
                ];
            }
        }

        $template = $production->getType() === 'soja' 
            ? 'pdf/global_soja_report.html.twig'
            : 'pdf/global_cereales_report.html.twig';

        $html = $this->twig->render($template, [
            'production' => $production,
            'ofs_data' => $globalData,
            'generated_at' => new \DateTime()
        ]);

        return $this->convertHtmlToPdf($html);
    }

    /**
     * Récupère toutes les données pour un rapport SOJA
     */
    public function getSojaReportData(OF $of): array
    {
        return [
            'haccp' => $this->haccpRepository->findByOF($of->getId()),
            'analyses' => $this->analyseSojaRepository->findByOFOrderedByTime($of->getId()),
            'av_corrections' => $this->avCorrectSojaRepository->findByOF($of->getId()),
            'ap_corrections' => $this->apCorrectSojaRepository->findByOF($of->getId()),
            'okara' => $this->okaraRepository->findByOF($of->getId()),
            'summary' => $this->calculateSojaSummary($of)
        ];
    }

    /**
     * Récupère toutes les données pour un rapport CÉRÉALES
     */
    public function getCerealesReportData(OF $of): array
    {
        return [
            'haccp' => $this->haccpRepository->findByOF($of->getId()),
            'cuves' => $this->cuveCerealesRepository->findByOFOrderedByTime($of->getId()),
            'hydrolyses' => $this->heureEnzymeRepository->findByOFOrderedByTime($of->getId()),
            'decanteurs' => $this->decanteurCerealesRepository->findByOFOrderedByTime($of->getId()),
            'av_corrections' => $this->avCorrectCerealesRepository->findByOF($of->getId()),
            'ap_corrections' => $this->apCorrectCerealesRepository->findByOF($of->getId()),
            'summary' => $this->calculateCerealesSummary($of)
        ];
    }

    /**
     * Calcule le résumé des données SOJA
     */
    private function calculateSojaSummary(OF $of): array
    {
        $analyses = $this->analyseSojaRepository->findByOFOrderedByTime($of->getId());
        $avCorrections = $this->avCorrectSojaRepository->findByOF($of->getId());
        $apCorrections = $this->apCorrectSojaRepository->findByOF($of->getId());
        $okara = $this->okaraRepository->findByOF($of->getId());

        return [
            'total_analyses' => count($analyses),
            'duree_production' => $this->calculateProductionDuration($analyses),
            'moyenne_ph' => $this->calculateAveragePH($analyses),
            'moyenne_brix' => $this->calculateAverageBrix($analyses),
            'total_corrections_av' => count($avCorrections),
            'total_corrections_ap' => count($apCorrections),
            'quantite_okara_totale' => array_sum(array_map(fn($o) => $o->getQuantite(), $okara)),
            'conformite_globale' => $this->checkGlobalConformity($analyses)
        ];
    }

    /**
     * Calcule le résumé des données CÉRÉALES
     */
    private function calculateCerealesSummary(OF $of): array
    {
        $cuves = $this->cuveCerealesRepository->findByOFOrderedByTime($of->getId());
        $hydrolyses = $this->heureEnzymeRepository->findByOFOrderedByTime($of->getId());
        $decanteurs = $this->decanteurCerealesRepository->findByOFOrderedByTime($of->getId());
        $avCorrections = $this->avCorrectCerealesRepository->findByOF($of->getId());
        $apCorrections = $this->apCorrectCerealesRepository->findByOF($of->getId());

        // Calculer les statistiques d'hydrolyse
        $hydrolyseStats = $this->heureEnzymeRepository->getOFHydrolysisStats($of->getId());

        return [
            'total_cuves' => count($cuves),
            'total_hydrolyses' => count($hydrolyses),
            'total_decanteurs' => count($decanteurs),
            'duree_production' => $this->calculateProductionDurationCereales($cuves, $decanteurs),
            'moyenne_ph_cuves' => $this->calculateAveragePHCuves($cuves),
            'moyenne_ph_decanteurs' => $this->calculateAveragePHDecanteurs($decanteurs),
            'efficacite_hydrolyse_moyenne' => $hydrolyseStats['efficacite_moyenne'],
            'duree_hydrolyse_moyenne' => $hydrolyseStats['duree_moyenne'],
            'total_corrections_av' => count($avCorrections),
            'total_corrections_ap' => count($apCorrections),
            'conformite_globale' => $this->checkGlobalConformityCerealesWithHydrolysis($cuves, $hydrolyses, $decanteurs)
        ];
    }

    /**
     * Convertit le HTML en PDF using wkhtmltopdf
     */
    private function convertHtmlToPdf(string $html): string
    {
        $tempHtmlFile = tempnam(sys_get_temp_dir(), 'report_') . '.html';
        $tempPdfFile = tempnam(sys_get_temp_dir(), 'report_') . '.pdf';

        try {
            // Écrire le HTML dans un fichier temporaire
            file_put_contents($tempHtmlFile, $html);

            // Commande wkhtmltopdf avec options
            $command = [
                'wkhtmltopdf',
                '--page-size', 'A4',
                '--orientation', 'Portrait',
                '--margin-top', '20mm',
                '--margin-right', '15mm',
                '--margin-bottom', '20mm',
                '--margin-left', '15mm',
                '--encoding', 'UTF-8',
                '--enable-local-file-access',
                $tempHtmlFile,
                $tempPdfFile
            ];

            $process = new Process($command);
            $process->setTimeout(60);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new \RuntimeException('Erreur lors de la génération du PDF: ' . $process->getErrorOutput());
            }

            if (!file_exists($tempPdfFile)) {
                throw new \RuntimeException('Le fichier PDF n\'a pas été généré');
            }

            $pdfContent = file_get_contents($tempPdfFile);

            return $pdfContent;

        } finally {
            // Nettoyer les fichiers temporaires
            if (file_exists($tempHtmlFile)) {
                unlink($tempHtmlFile);
            }
            if (file_exists($tempPdfFile)) {
                unlink($tempPdfFile);
            }
        }
    }

    /**
     * Calcule la durée de production basée sur les analyses
     */
    private function calculateProductionDuration(array $analyses): ?string
    {
        if (count($analyses) < 2) {
            return null;
        }

        usort($analyses, fn($a, $b) => $a->getHeureAnalyse() <=> $b->getHeureAnalyse());
        
        $debut = reset($analyses)->getHeureAnalyse();
        $fin = end($analyses)->getHeureAnalyse();
        
        $diff = $debut->diff($fin);
        return $diff->format('%H:%I');
    }

    /**
     * Calcule la durée de production CÉRÉALES
     */
    private function calculateProductionDurationCereales(array $cuves, array $decanteurs): ?string
    {
        $allTimes = [];
        
        foreach ($cuves as $cuve) {
            $allTimes[] = $cuve->getHeureDebut();
            if ($cuve->getHeureFin()) {
                $allTimes[] = $cuve->getHeureFin();
            }
        }
        
        foreach ($decanteurs as $decanteur) {
            $allTimes[] = $decanteur->getHeureDebut();
            if ($decanteur->getHeureFin()) {
                $allTimes[] = $decanteur->getHeureFin();
            }
        }

        if (count($allTimes) < 2) {
            return null;
        }

        sort($allTimes);
        $diff = reset($allTimes)->diff(end($allTimes));
        return $diff->format('%H:%I');
    }

    /**
     * Calcule la moyenne du pH
     */
    private function calculateAveragePH(array $analyses): ?float
    {
        if (empty($analyses)) {
            return null;
        }

        $phValues = array_filter(array_map(fn($a) => $a->getPh(), $analyses));
        return empty($phValues) ? null : round(array_sum($phValues) / count($phValues), 2);
    }

    /**
     * Calcule la moyenne du Brix
     */
    private function calculateAverageBrix(array $analyses): ?float
    {
        if (empty($analyses)) {
            return null;
        }

        $brixValues = array_filter(array_map(fn($a) => $a->getBrix(), $analyses));
        return empty($brixValues) ? null : round(array_sum($brixValues) / count($brixValues), 2);
    }

    /**
     * Calcule la moyenne du pH pour les cuves
     */
    private function calculateAveragePHCuves(array $cuves): ?float
    {
        if (empty($cuves)) {
            return null;
        }

        $phValues = array_filter(array_map(fn($c) => $c->getPh(), $cuves));
        return empty($phValues) ? null : round(array_sum($phValues) / count($phValues), 2);
    }

    /**
     * Calcule la moyenne du pH pour les décanteurs
     */
    private function calculateAveragePHDecanteurs(array $decanteurs): ?float
    {
        if (empty($decanteurs)) {
            return null;
        }

        $phValues = array_filter(array_map(fn($d) => $d->getPh(), $decanteurs));
        return empty($phValues) ? null : round(array_sum($phValues) / count($phValues), 2);
    }

    /**
     * Vérifie la conformité globale SOJA
     */
    private function checkGlobalConformity(array $analyses): bool
    {
        foreach ($analyses as $analyse) {
            if (!$analyse->getConformite()) {
                return false;
            }
        }
        return true;
    }

    /**
     * Vérifie la conformité globale CÉRÉALES
     */
    private function checkGlobalConformityCereales(array $cuves, array $decanteurs): bool
    {
        foreach ($cuves as $cuve) {
            if (!$cuve->getConformite()) {
                return false;
            }
        }
        
        foreach ($decanteurs as $decanteur) {
            if (!$decanteur->getConformite()) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Vérifie la conformité globale CÉRÉALES avec hydrolyse enzymatique
     */
    private function checkGlobalConformityCerealesWithHydrolysis(array $cuves, array $hydrolyses, array $decanteurs): bool
    {
        foreach ($cuves as $cuve) {
            if (!$cuve->getConformite()) {
                return false;
            }
        }
        
        foreach ($hydrolyses as $hydrolyse) {
            if (!$hydrolyse->getConformite()) {
                return false;
            }
        }
        
        foreach ($decanteurs as $decanteur) {
            if (!$decanteur->getConformite()) {
                return false;
            }
        }
        
        return true;
    }
}
