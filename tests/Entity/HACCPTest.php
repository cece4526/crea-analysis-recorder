<?php
namespace App\Tests\Entity;

use App\Entity\HACCP;
use App\Entity\OF;
use PHPUnit\Framework\TestCase;

/**
 * Test unitaire pour l'entité HACCP.
 *
 * @category Test
 * @package  App\Tests\Entity
 * @author   cece4526 <contact@example.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class HACCPTest extends TestCase
{
    /**
     * Teste tous les getters et setters de l'entité HACCP.
     *
     * @return void
     */
    public function testGettersAndSetters()
    {
        $entity = new HACCP();
        $this->assertNull($entity->getId());

        $entity->setFiltrePasteurisateurResultat(true);
        $this->assertTrue($entity->getFiltrePasteurisateurResultat());

        $entity->setTemperatureCible(80);
        $this->assertSame(80, $entity->getTemperatureCible());

        $entity->setTemperatureIndique(78);
        $this->assertSame(78, $entity->getTemperatureIndique());

        $entity->setFiltreNepResultat(false);
        $this->assertFalse($entity->getFiltreNepResultat());

        $of = $this->createMock(OF::class);
        $entity->setOf($of);
        $this->assertSame($of, $entity->getOf());
    }
}
