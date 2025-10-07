<?php
namespace App\Tests\Entity;

use App\Entity\DecanteurCereales;
use App\Entity\OF;
use PHPUnit\Framework\TestCase;

/**
 * Test unitaire pour l'entité DecanteurCereales.
 *
 * @category Test
 * @package  App\Tests\Entity
 * @author   cece4526 <contact@example.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class DecanteurCerealesTest extends TestCase
{
    /**
     * Teste tous les getters et setters de l'entité DecanteurCereales.
     *
     * @return void
     */
    public function testGettersAndSetters()
    {
        $entity = new DecanteurCereales();
        $this->assertNull($entity->getId());

        $of = $this->createMock(OF::class);
        $entity->setOf($of);
        $this->assertSame($of, $entity->getOf());

        $entity->setEsAvDecan('1.1');
        $this->assertSame('1.1', $entity->getEsAvDecan());

        $entity->setEsApDecan('2.2');
        $this->assertSame('2.2', $entity->getEsApDecan());

        $entity->setVitesseDiff('3.3');
        $this->assertSame('3.3', $entity->getVitesseDiff());

        $entity->setVariponds('4.4');
        $this->assertSame('4.4', $entity->getVariponds());

        $entity->setCouple('5.5');
        $this->assertSame('5.5', $entity->getCouple());

        $entity->setContrePression('6.6');
        $this->assertSame('6.6', $entity->getContrePression());
    }
}
