<?php
namespace App\Tests\Entity;
use App\Entity\AnalyseSoja;
use App\Entity\OF;
use PHPUnit\Framework\TestCase;
/**
 * Test unitaire pour l'entité AnalyseSoja.
 *
 * @category Test
 * @package  App\Tests\Entity
 * @author   cece4526 <contact@example.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class AnalyseSojaTest extends TestCase
{
    /**
     * Teste tous les getters et setters de l'entité AnalyseSoja.
     *
     * @return void
     */
    public function testAllGettersAndSetters()
    {
        $entity = new AnalyseSoja();
        $of = $this->createMock(OF::class);
        $entity->setOf($of);
        $this->assertSame($of, $entity->getOf());

        $date = new \DateTime('2025-10-01');
        $entity->setDate($date);
        $this->assertSame($date, $entity->getDate());

        $entity->setLitrageDecan(123);
        $this->assertSame(123, $entity->getLitrageDecan());

        $entity->setTemperatureBroyage('45.5');
        $this->assertSame('45.5', $entity->getTemperatureBroyage());

        $entity->setEau(10);
        $this->assertSame(10, $entity->getEau());

        $entity->setMatiere(20);
        $this->assertSame(20, $entity->getMatiere());

        $entity->setEsAvDecan('5.2');
        $this->assertSame('5.2', $entity->getEsAvDecan());

        $entity->setEsApDecan('6.3');
        $this->assertSame('6.3', $entity->getEsApDecan());

    $entity->setControlVisuel(true);
    $this->assertTrue($entity->isControlVisuel());

    $entity->setDebitBicar('7.8');
    $this->assertSame('7.8', $entity->getDebitBicar());

    $entity->setVitesseDiff('8.9');
    $this->assertSame('8.9', $entity->getVitesseDiff());

    $entity->setCouple('9.1');
    $this->assertSame('9.1', $entity->getCouple());

    $entity->setVariponds('10.2');
    $this->assertSame('10.2', $entity->getVariponds());

    $entity->setContrePression('11.3');
    $this->assertSame('11.3', $entity->getContrePression());

    $entity->setInitialPilote('PiloteX');
    $this->assertSame('PiloteX', $entity->getInitialPilote());
    }
}
