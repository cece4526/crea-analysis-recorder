<?php
namespace App\Tests\Entity;
use App\Entity\ApCorrectCereales;
use App\Entity\OF;
use PHPUnit\Framework\TestCase;
/**
 * Test unitaire pour l'entité ApCorrectCereales.
 *
 * @category Test
 * @package  App\Tests\Entity
 * @author   cece4526 <contact@example.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class ApCorrectCerealesTest extends TestCase
{
    /**
     * Teste les getters et setters de ApCorrectCereales.
     *
     * @return void
     */
    public function testGettersAndSetters()
    {
        $entity = new ApCorrectCereales();
        $of = $this->createMock(OF::class);
        $entity->setOf($of);
        $entity->setDate(new \DateTime('2025-10-01'));
        $entity->setTank(2);
        $this->assertSame($of, $entity->getOf());
        $this->assertInstanceOf(\DateTimeInterface::class, $entity->getDate());
        $this->assertSame(2, $entity->getTank());
    }

    /**
     * Teste tous les getters et setters de ApCorrectCereales.
     *
     * @return void
     */
    public function testAllGettersAndSetters()
    {
        $entity = new ApCorrectCereales();
        $this->assertNull($entity->getId());

        $date = new \DateTime('2025-10-01');
        $entity->setDate($date);
        $this->assertSame($date, $entity->getDate());

        $entity->setTank(1);
        $this->assertSame(1, $entity->getTank());

        $entity->setEauAjouter(2);
        $this->assertSame(2, $entity->getEauAjouter());

        $entity->setProduitFini(3);
        $this->assertSame(3, $entity->getProduitFini());

        $entity->setEsTank('4.4');
        $this->assertSame('4.4', $entity->getEsTank());

        $entity->setCulot('5.5');
        $this->assertSame('5.5', $entity->getCulot());

        $entity->setPh('6.6');
        $this->assertSame('6.6', $entity->getPh());

        $entity->setDensiter('7.7');
        $this->assertSame('7.7', $entity->getDensiter());

        $entity->setSucre('8.8');
        $this->assertSame('8.8', $entity->getSucre());

        $entity->setCryoscopie('9.9');
        $this->assertSame('9.9', $entity->getCryoscopie());

        $entity->setInitialPilote('PiloteX');
        $this->assertSame('PiloteX', $entity->getInitialPilote());

        $of = $this->createMock(OF::class);
        $entity->setOf($of);
        $this->assertSame($of, $entity->getOf());
    }
}
