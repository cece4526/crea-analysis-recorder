<?php
namespace App\Tests\Entity;

use App\Entity\CuveCereales;
use App\Entity\OF;
use PHPUnit\Framework\TestCase;

/**
 * Test unitaire pour l'entité CuveCereales.
 *
 * @category Test
 * @package  App\Tests\Entity
 * @author   cece4526 <contact@example.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class CuveCerealesTest extends TestCase
{
    /**
     * Teste tous les getters et setters de l'entité CuveCereales.
     *
     * @return void
     */
    public function testGettersAndSetters()
    {
        $entity = new CuveCereales();
        $this->assertNull($entity->getId());

        $of = $this->createMock(OF::class);
        $entity->setOf($of);
        $this->assertSame($of, $entity->getOf());

        $entity->setCuve(5);
        $this->assertSame(5, $entity->getCuve());

        $entity->setDebitEnzyme('1.1');
        $this->assertSame('1.1', $entity->getDebitEnzyme());

        $entity->setTemperatureHydrolise('2.2');
        $this->assertSame('2.2', $entity->getTemperatureHydrolise());

        $entity->setQuantiteEnzyme2('3.3');
        $this->assertSame('3.3', $entity->getQuantiteEnzyme2());

        $entity->setMatiere('4.4');
        $this->assertSame('4.4', $entity->getMatiere());

        $entity->setControlVerre(true);
        $this->assertTrue($entity->getControlVerre());

        $entity->setInitialPilote('PiloteX');
        $this->assertSame('PiloteX', $entity->getInitialPilote());
    }
}
