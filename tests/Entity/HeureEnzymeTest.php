<?php
namespace App\Tests\Entity;

use App\Entity\HeureEnzyme;
use App\Entity\OF;
use PHPUnit\Framework\TestCase;

/**
 * Test unitaire pour l'entité HeureEnzyme.
 *
 * @category Test
 * @package  App\Tests\Entity
 * @author   cece4526 <contact@example.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class HeureEnzymeTest extends TestCase
{
    /**
     * Teste tous les getters et setters de l'entité HeureEnzyme.
     *
     * @return void
     */
    public function testGettersAndSetters()
    {
        $entity = new HeureEnzyme();
        $this->assertNull($entity->getId());

        $date = new \DateTime('2025-10-01 12:00:00');
        $entity->setHeure($date);
        $this->assertSame($date, $entity->getHeure());

        $of = $this->createMock(OF::class);
        $entity->setOf($of);
        $this->assertSame($of, $entity->getOf());
    }
}
