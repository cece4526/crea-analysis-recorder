<?php

namespace App\Tests\Repository;

use App\Entity\AnalyseSoja;
use App\Entity\OF;
use App\Repository\AnalyseSojaRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AnalyseSojaRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;
    private ?AnalyseSojaRepository $repository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->repository = $this->entityManager->getRepository(AnalyseSoja::class);

        // Créer les tables pour les tests
        $this->createSchema();
    }

    private function createSchema(): void
    {
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        
        // Supprimer et recréer les tables
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Nettoyer
        $this->entityManager->close();
        $this->entityManager = null;
        $this->repository = null;
    }

    public function testFindAllWithOF(): void
    {
        // Créer un OF de test (simplifié pour éviter les relations complexes)  
        $of = $this->createSimpleOF(12345, 'Test OF');

        // Créer une analyse de test
        $analyse = new AnalyseSoja();
        $analyse->setOf($of);
        $analyse->setDate(new \DateTime());
        $analyse->setLitrageDecan(1000);
        $analyse->setEau(500);
        $analyse->setMatiere(200);
        $analyse->setControlVisuel(true);
        $analyse->setInitialPilote('AB');

        $this->entityManager->persist($analyse);
        $this->entityManager->flush();

        // Test de la méthode
        $analyses = $this->repository->findAllWithOF();

        $this->assertCount(1, $analyses);
        $this->assertInstanceOf(AnalyseSoja::class, $analyses[0]);
        $this->assertInstanceOf(OF::class, $analyses[0]->getOf());
        $this->assertEquals(12345, $analyses[0]->getOf()->getNumero());
    }

    public function testFindByOFNumero(): void
    {
        // Créer un OF
        $of = $this->createSimpleOF(99999, 'Test OF Numero');

        // Créer une analyse
        $analyse = new AnalyseSoja();
        $analyse->setOf($of);
        $analyse->setDate(new \DateTime());
        $analyse->setLitrageDecan(800);
        $analyse->setEau(400);
        $analyse->setMatiere(150);
        $analyse->setControlVisuel(true);

        $this->entityManager->persist($analyse);
        $this->entityManager->flush();

        // Test de la méthode
        $analyses = $this->repository->findByOFNumero(99999);

        $this->assertCount(1, $analyses);
        $this->assertEquals(99999, $analyses[0]->getOf()->getNumero());
    }

    public function testFindByControlVisuel(): void
    {
        // Créer un OF
        $of = $this->createSimpleOF(77777, 'Test OF Control');

        // Créer des analyses avec différents contrôles visuels
        $analyse1 = new AnalyseSoja();
        $analyse1->setOf($of);
        $analyse1->setDate(new \DateTime());
        $analyse1->setLitrageDecan(1000);
        $analyse1->setEau(500);
        $analyse1->setMatiere(200);
        $analyse1->setControlVisuel(true);

        $analyse2 = new AnalyseSoja();
        $analyse2->setOf($of);
        $analyse2->setDate(new \DateTime());
        $analyse2->setLitrageDecan(1100);
        $analyse2->setEau(550);
        $analyse2->setMatiere(220);
        $analyse2->setControlVisuel(false);

        $this->entityManager->persist($analyse1);
        $this->entityManager->persist($analyse2);
        $this->entityManager->flush();

        // Test contrôle visuel réussi
        $analysesOK = $this->repository->findByControlVisuel(true);
        $this->assertCount(1, $analysesOK);
        $this->assertTrue($analysesOK[0]->isControlVisuel());

        // Test contrôle visuel échoué
        $analysesKO = $this->repository->findByControlVisuel(false);
        $this->assertCount(1, $analysesKO);
        $this->assertFalse($analysesKO[0]->isControlVisuel());
    }

    public function testFindByPilote(): void
    {
        // Créer un OF
        $of = $this->createSimpleOF(66666, 'Test OF Pilote');

        // Créer des analyses avec différents pilotes
        $analyse1 = new AnalyseSoja();
        $analyse1->setOf($of);
        $analyse1->setDate(new \DateTime());
        $analyse1->setLitrageDecan(1000);
        $analyse1->setEau(500);
        $analyse1->setMatiere(200);
        $analyse1->setControlVisuel(true);
        $analyse1->setInitialPilote('AB');

        $analyse2 = new AnalyseSoja();
        $analyse2->setOf($of);
        $analyse2->setDate(new \DateTime());
        $analyse2->setLitrageDecan(1100);
        $analyse2->setEau(550);
        $analyse2->setMatiere(220);
        $analyse2->setControlVisuel(true);
        $analyse2->setInitialPilote('CD');

        $this->entityManager->persist($analyse1);
        $this->entityManager->persist($analyse2);
        $this->entityManager->flush();

        // Test recherche par pilote
        $analysesAB = $this->repository->findByPilote('AB');
        $this->assertCount(1, $analysesAB);
        $this->assertEquals('AB', $analysesAB[0]->getInitialPilote());
    }

    public function testCountByControlVisuel(): void
    {
        // Créer un OF
        $of = $this->createSimpleOF(55555, 'Test OF Count');

        // Créer des analyses avec différents contrôles visuels
        for ($i = 0; $i < 3; $i++) {
            $analyse = new AnalyseSoja();
            $analyse->setOf($of);
            $analyse->setDate(new \DateTime());
            $analyse->setLitrageDecan(1000 + $i * 100);
            $analyse->setEau(500 + $i * 50);
            $analyse->setMatiere(200 + $i * 20);
            $analyse->setControlVisuel(true);
            $this->entityManager->persist($analyse);
        }

        for ($i = 0; $i < 2; $i++) {
            $analyse = new AnalyseSoja();
            $analyse->setOf($of);
            $analyse->setDate(new \DateTime());
            $analyse->setLitrageDecan(1000 + $i * 100);
            $analyse->setEau(500 + $i * 50);
            $analyse->setMatiere(200 + $i * 20);
            $analyse->setControlVisuel(false);
            $this->entityManager->persist($analyse);
        }

        $this->entityManager->flush();

        // Test du comptage
        $stats = $this->repository->countByControlVisuel();

        $this->assertEquals(3, $stats['reussi']);
        $this->assertEquals(2, $stats['echec']);
        $this->assertEquals(0, $stats['null']);
    }

    public function testGetTemperatureStats(): void
    {
        // Créer un OF
        $of = $this->createSimpleOF(44444, 'Test OF Temperature');

        // Créer des analyses avec différentes températures
        $temperatures = ['65.5', '70.0', '68.2', '72.1'];

        foreach ($temperatures as $temp) {
            $analyse = new AnalyseSoja();
            $analyse->setOf($of);
            $analyse->setDate(new \DateTime());
            $analyse->setLitrageDecan(1000);
            $analyse->setEau(500);
            $analyse->setMatiere(200);
            $analyse->setControlVisuel(true);
            $analyse->setTemperatureBroyage($temp);
            $this->entityManager->persist($analyse);
        }

        $this->entityManager->flush();

        // Test des statistiques
        $stats = $this->repository->getTemperatureStats();

        $this->assertEquals(4, $stats['total']);
        $this->assertEquals('65.50', $stats['minimum']);
        $this->assertEquals('72.10', $stats['maximum']);
        $this->assertGreaterThan(67, (float)$stats['moyenne']);
        $this->assertLessThan(70, (float)$stats['moyenne']);
    }

    public function testSaveAndRemove(): void
    {
        // Créer un OF
        $of = $this->createSimpleOF(33333, 'Test OF Save');

        // Test save
        $analyse = new AnalyseSoja();
        $analyse->setOf($of);
        $analyse->setDate(new \DateTime());
        $analyse->setLitrageDecan(1000);
        $analyse->setEau(500);
        $analyse->setMatiere(200);
        $analyse->setControlVisuel(true);

        $this->repository->save($analyse, true);

        $this->assertNotNull($analyse->getId());

        // Test remove
        $id = $analyse->getId();
        $this->repository->remove($analyse, true);

        $analyseSupprimee = $this->repository->find($id);
        $this->assertNull($analyseSupprimee);
    }

    /**
     * Méthode utilitaire pour créer un OF simplifié
     */
    private function createSimpleOF(int $numero, string $name): OF
    {
        $of = new OF();
        $of->setNumero($numero);
        $of->setName($name);
        $of->setNature('Soja');
        $of->setDate(new \DateTime());

        $this->entityManager->persist($of);
        $this->entityManager->flush();

        return $of;
    }
}
