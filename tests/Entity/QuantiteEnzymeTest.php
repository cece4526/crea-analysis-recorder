<?php
namespace App\Tests\Entity;

use App\Entity\QuantiteEnzyme;
use App\Entity\Enzyme;
use App\Entity\OF;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Test unitaire pour l'entité QuantiteEnzyme.
 *
 * @category Test
 * @package  App\Tests\Entity
 * @author   cece4526
 * @license  MIT
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class QuantiteEnzymeTest extends TestCase
{
    /**
     * Teste les getters et setters de QuantiteEnzyme.
     *
     * @return void
     */
    public function testGettersAndSetters()
    {
        $entity = new QuantiteEnzyme();
        $of = $this->createMock(OF::class);
        $enzyme = $this->createMock(Enzyme::class);
        $entity->setPourcentage('12.5');
        $entity->setQuantite('42.0');
        $entity->setOf($of);
        $entity->addEnzyme($enzyme);
        $this->assertSame('12.5', $entity->getPourcentage());
        $this->assertSame('42.0', $entity->getQuantite());
        $this->assertSame($of, $entity->getOf());
        $this->assertTrue($entity->getEnzymes()->contains($enzyme));
        $entity->removeEnzyme($enzyme);
        $this->assertFalse($entity->getEnzymes()->contains($enzyme));
    }
}
