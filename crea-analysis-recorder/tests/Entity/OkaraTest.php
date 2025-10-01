<?php
namespace App\Tests\Entity;

use App\Entity\Okara;
use App\Entity\OF;
use App\Entity\Echantillons;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

/**
 * Test unitaire pour l'entité Okara.
 *
 * @category Test
 * @package  App\Tests\Entity
 * @author   cece4526 <contact@example.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class OkaraTest extends TestCase
{
    /**
     * Teste tous les getters et setters de l'entité Okara.
     *
     * @return void
     */
    public function testGettersAndSetters()
    {
        $entity = new Okara();
        $this->assertNull($entity->getId());

        $of = $this->createMock(OF::class);
        $entity->setOf($of);
        $this->assertSame($of, $entity->getOf());
    }

    /**
     * Teste la gestion de la collection d'échantillons.
     *
     * @return void
     */
    public function testEchantillonsCollection()
    {
        $entity = new Okara();
        $echantillon = $this->createMock(Echantillons::class);
        $this->assertInstanceOf(ArrayCollection::class, $entity->getEchantillons());
        $this->assertCount(0, $entity->getEchantillons());

        $entity->addEchantillon($echantillon);
        $this->assertCount(1, $entity->getEchantillons());
        $this->assertTrue($entity->getEchantillons()->contains($echantillon));

        $entity->removeEchantillon($echantillon);
        $this->assertCount(0, $entity->getEchantillons());
    }
}
