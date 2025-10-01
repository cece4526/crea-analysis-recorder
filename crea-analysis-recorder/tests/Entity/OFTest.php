
<?php
namespace App\Tests\Entity;

use App\Entity\OF;
use App\Entity\Production;
use PHPUnit\Framework\TestCase;

/**
 * Test unitaire pour l'entité OF.
 *
 * @category Test
 * @package  App\Tests\Entity
 * @author   cece4526
 * @license  MIT
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class OFTest extends TestCase
{
    /**
     * Teste les getters et setters de OF.
     *
     * @return void
     */
    public function testGettersAndSetters()
    {
        $entity = new OF();
        $entity->setName('OF1');
        $entity->setNumero(123);
        $entity->setNature('Nature');
        $entity->setProduction($this->createMock(Production::class));
        $entity->setDate(new \DateTime('2025-10-01'));
        $this->assertSame('OF1', $entity->getName());
        $this->assertSame(123, $entity->getNumero());
        $this->assertSame('Nature', $entity->getNature());
        $this->assertInstanceOf(Production::class, $entity->getProduction());
        $this->assertInstanceOf(\DateTimeInterface::class, $entity->getDate());
    }
}
