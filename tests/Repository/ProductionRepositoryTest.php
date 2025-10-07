<?php
namespace App\Tests\Repository;

use App\Entity\Production;
use App\Repository\ProductionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Tests pour ProductionRepository avec base de données en mémoire
 */
class ProductionRepositoryTest extends KernelTestCase
{
    private ProductionRepository $repository;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->repository = static::getContainer()->get(ProductionRepository::class);
        
        // Créer le schéma de base de données pour les tests
        $this->createSchema();
    }

    private function createSchema(): void
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    public function testBasicRepositoryOperations(): void
    {
        // Test findAll - doit retourner un tableau vide au début
        $result = $this->repository->findAll();
        $this->assertIsArray($result);
        $this->assertEmpty($result);
        
        // Test find avec un ID inexistant
        $entity = $this->repository->find(999);
        $this->assertNull($entity);
    }

    public function testCustomRepositoryMethods(): void
    {
        // Test des méthodes personnalisées - doivent retourner des tableaux vides
        $this->assertIsArray($this->repository->findAllWithOF());
        $this->assertIsArray($this->repository->findActiveProductions());
        $this->assertIsArray($this->repository->findByStatus('En cours'));
        $this->assertIsArray($this->repository->findByMinQuantity(10));
        $this->assertIsArray($this->repository->searchByName('test'));
        
        // Test des méthodes de comptage
        $this->assertIsArray($this->repository->countByStatus());
        $this->assertIsInt($this->repository->getTotalQuantity());
        $this->assertEquals(0, $this->repository->getTotalQuantity());
    }

    public function testSaveAndRetrieveProduction(): void
    {
        // Créer une production de test
        $production = new Production();
        $production->setName('Test Production');
        $production->setQuantity(100);
        $production->setStatus('En cours');

        // Sauvegarder
        $this->repository->save($production, true);
        $this->assertNotNull($production->getId());

        // Récupérer et vérifier
        $id = $production->getId();
        $savedProduction = $this->repository->find($id);
        $this->assertNotNull($savedProduction);
        $this->assertEquals('Test Production', $savedProduction->getName());
        $this->assertEquals(100, $savedProduction->getQuantity());
        $this->assertEquals('En cours', $savedProduction->getStatus());
    }

    public function testCustomQueriesWithData(): void
    {
        // Créer des données de test
        $production1 = new Production();
        $production1->setName('Production Soja');
        $production1->setQuantity(500);
        $production1->setStatus('En cours');

        $production2 = new Production();
        $production2->setName('Production Riz');
        $production2->setQuantity(300);
        $production2->setStatus('Terminée');

        $this->repository->save($production1, false);
        $this->repository->save($production2, true);

        // Test findByStatus
        $enCours = $this->repository->findByStatus('En cours');
        $this->assertCount(1, $enCours);
        $this->assertEquals('Production Soja', $enCours[0]->getName());

        // Test findActiveProductions
        $actives = $this->repository->findActiveProductions();
        $this->assertCount(1, $actives);

        // Test findByMinQuantity
        $grosses = $this->repository->findByMinQuantity(400);
        $this->assertCount(1, $grosses);

        // Test searchByName
        $soja = $this->repository->searchByName('Soja');
        $this->assertCount(1, $soja);

        // Test getTotalQuantity
        $total = $this->repository->getTotalQuantity();
        $this->assertEquals(800, $total);

        // Test countByStatus
        $statusCount = $this->repository->countByStatus();
        $this->assertArrayHasKey('En cours', $statusCount);
        $this->assertArrayHasKey('Terminée', $statusCount);
        $this->assertEquals(1, $statusCount['En cours']);
        $this->assertEquals(1, $statusCount['Terminée']);
    }

    public function testRemoveProduction(): void
    {
        // Créer et sauvegarder une production
        $production = new Production();
        $production->setName('Test Remove');
        $this->repository->save($production, true);
        $id = $production->getId();

        // Vérifier qu'elle existe
        $found = $this->repository->find($id);
        $this->assertNotNull($found);

        // Supprimer
        $this->repository->remove($found, true);

        // Vérifier qu'elle n'existe plus
        $deleted = $this->repository->find($id);
        $this->assertNull($deleted);
    }

    public function testQueryBuilderMethod(): void
    {
        $qb = $this->repository->createBaseQueryBuilder();
        $this->assertInstanceOf('Doctrine\ORM\QueryBuilder', $qb);
        
        // Tester que la requête peut être exécutée
        $results = $qb->getQuery()->getResult();
        $this->assertIsArray($results);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}
