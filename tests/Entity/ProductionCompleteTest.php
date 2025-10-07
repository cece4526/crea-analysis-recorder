<?php
namespace App\Tests\Entity;

use App\Entity\Production;
use App\Entity\OF;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Test unitaire complet pour l'entité Production.
 *
 * @category Test
 * @package  App\Tests\Entity
 * @author   cece4526 <contact@example.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class ProductionCompleteTest extends TestCase
{
    private Production $production;

    /**
     * Configuration avant chaque test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->production = new Production();
    }

    /**
     * Teste l'instanciation de l'entité Production.
     *
     * @return void
     */
    public function testInstantiation()
    {
        $this->assertInstanceOf(Production::class, $this->production);
        $this->assertNull($this->production->getId());
        $this->assertNull($this->production->getName());
        $this->assertEquals(0, $this->production->getQuantity());
        $this->assertNull($this->production->getStatus());
        
        // Vérifier que les OFs sont initialisés comme une collection vide
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $this->production->getOfs());
        $this->assertTrue($this->production->getOfs()->isEmpty());
    }

    /**
     * Teste les getters et setters de base.
     *
     * @return void
     */
    public function testBasicGettersAndSetters()
    {
        // Test du nom
        $this->production->setName('Production Test');
        $this->assertEquals('Production Test', $this->production->getName());

        // Test de la quantité
        $this->production->setQuantity(100);
        $this->assertEquals(100, $this->production->getQuantity());

        // Test du statut
        $this->production->setStatus('En cours');
        $this->assertEquals('En cours', $this->production->getStatus());
    }

    /**
     * Teste le chaînage des méthodes (fluent interface).
     *
     * @return void
     */
    public function testFluentInterface()
    {
        $result = $this->production
            ->setName('Production Fluide')
            ->setQuantity(200)
            ->setStatus('Terminée');

        $this->assertSame($this->production, $result);
        $this->assertEquals('Production Fluide', $this->production->getName());
        $this->assertEquals(200, $this->production->getQuantity());
        $this->assertEquals('Terminée', $this->production->getStatus());
    }

    /**
     * Teste la gestion de la collection d'OFs.
     *
     * @return void
     */
    public function testOfsCollection()
    {
        // Test avec un mock d'OF
        $of1 = $this->createMock(OF::class);
        $of2 = $this->createMock(OF::class);

        // Test d'ajout d'OF
        $this->production->addOf($of1);
        $this->assertTrue($this->production->getOfs()->contains($of1));
        $this->assertEquals(1, $this->production->getOfs()->count());

        // Test d'ajout d'un deuxième OF
        $this->production->addOf($of2);
        $this->assertTrue($this->production->getOfs()->contains($of2));
        $this->assertEquals(2, $this->production->getOfs()->count());

        // Test de suppression d'OF
        $this->production->removeOf($of1);
        $this->assertFalse($this->production->getOfs()->contains($of1));
        $this->assertTrue($this->production->getOfs()->contains($of2));
        $this->assertEquals(1, $this->production->getOfs()->count());

        // Test de suppression du dernier OF
        $this->production->removeOf($of2);
        $this->assertTrue($this->production->getOfs()->isEmpty());
    }

    /**
     * Teste les valeurs limites et cas particuliers.
     *
     * @return void
     */
    public function testEdgeCases()
    {
        // Test avec une chaîne vide pour le nom
        $this->production->setName('');
        $this->assertEquals('', $this->production->getName());

        // Test avec null pour le nom
        $this->production->setName(null);
        $this->assertNull($this->production->getName());

        // Test avec null pour le statut
        $this->production->setStatus(null);
        $this->assertNull($this->production->getStatus());

        // Test avec une quantité négative
        $this->production->setQuantity(-1);
        $this->assertEquals(-1, $this->production->getQuantity());

        // Test avec une quantité zéro
        $this->production->setQuantity(0);
        $this->assertEquals(0, $this->production->getQuantity());

        // Test avec une grande quantité
        $this->production->setQuantity(PHP_INT_MAX);
        $this->assertEquals(PHP_INT_MAX, $this->production->getQuantity());
    }

    /**
     * Teste les types de données.
     *
     * @return void
     */
    public function testDataTypes()
    {
        // Test que getName retourne bien une string ou null
        $this->production->setName('Test');
        $this->assertIsString($this->production->getName());
        
        $this->production->setName(null);
        $this->assertNull($this->production->getName());

        // Test que getQuantity retourne bien un entier
        $this->production->setQuantity(42);
        $this->assertIsInt($this->production->getQuantity());

        // Test que getStatus retourne bien une string ou null
        $this->production->setStatus('Active');
        $this->assertIsString($this->production->getStatus());
        
        $this->production->setStatus(null);
        $this->assertNull($this->production->getStatus());
    }

    /**
     * Teste la prévention de doublons dans la collection d'OFs.
     *
     * @return void
     */
    public function testNoDuplicatesInOfsCollection()
    {
        $of = $this->createMock(OF::class);

        // Ajouter le même OF deux fois
        $this->production->addOf($of);
        $this->production->addOf($of);

        // Vérifier qu'il n'y a qu'une seule instance
        $this->assertEquals(1, $this->production->getOfs()->count());
        $this->assertTrue($this->production->getOfs()->contains($of));
    }
}
