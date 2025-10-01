<?php
namespace App\Tests\Entity;

use App\Entity\Production;
use App\Entity\OF;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

/**
 * Test unitaire pour l'entité Production.
 *
 * @category Test
 * @package  App\Tests\Entity
 * @author   cece4526 <contact@example.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class ProductionTest extends TestCase
{
    /**
     * Teste tous les getters et setters de l'entité Production.
     *
     * @return void
     */
    public function testGettersAndSetters()
    {
        $entity = new Production();
        $this->assertNull($entity->getId());

        $entity->setName('Lot 2025');
        $this->assertSame('Lot 2025', $entity->getName());

        $entity->setQuantity(42);
        $this->assertSame(42, $entity->getQuantity());

        $entity->setStatus('En cours');
        $this->assertSame('En cours', $entity->getStatus());
    }

    /**
     * Teste la gestion de la collection d'OF.
     *
     * @return void
     */
    public function testOfsCollection()
    {
        $entity = new Production();
        $of = $this->createMock(OF::class);
        $this->assertInstanceOf(ArrayCollection::class, $entity->getOfs());
        $this->assertCount(0, $entity->getOfs());

        $entity->addOf($of);
        $this->assertCount(1, $entity->getOfs());
        $this->assertTrue($entity->getOfs()->contains($of));

        $entity->removeOf($of);
        $this->assertCount(0, $entity->getOfs());
    }
}
