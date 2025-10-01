<?php
namespace App\Tests\Entity;

use App\Entity\AvCorrectCereales;
use App\Entity\OF;
use PHPUnit\Framework\TestCase;

/**
 * Test unitaire pour l'entité AvCorrectCereales.
 *
 * @category Test
 * @package  App\Tests\Entity
 * @author   cece4526 <contact@example.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class AvCorrectCerealesTest extends TestCase
{
    /**
     * Teste tous les getters et setters de l'entité AvCorrectCereales.
     *
     * @return void
     */
    public function testGettersAndSetters()
    {
        $entity = new AvCorrectCereales();
        $this->assertNull($entity->getId());

        $date = new \DateTime('2025-10-01');
        $entity->setDate($date);
        $this->assertSame($date, $entity->getDate());

        $entity->setTank(1);
        $this->assertSame(1, $entity->getTank());

        $entity->setEau(2);
        $this->assertSame(2, $entity->getEau());

        $entity->setMatiere(3);
        $this->assertSame(3, $entity->getMatiere());

        $entity->setProduitFini(4);
        $this->assertSame(4, $entity->getProduitFini());

        $entity->setEsTank('5.5');
        $this->assertSame('5.5', $entity->getEsTank());

        $entity->setInitialPilote('PiloteX');
        $this->assertSame('PiloteX', $entity->getInitialPilote());

        $of = $this->createMock(OF::class);
        $entity->setOf($of);
        $this->assertSame($of, $entity->getOf());
    }
}
