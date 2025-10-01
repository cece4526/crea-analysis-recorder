
<?php
namespace App\Tests\Entity;

use App\Entity\Enzyme;
use App\Entity\QuantiteEnzyme;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Test unitaire pour l'entité Enzyme.
 *
 * @category Test
 * @package  App\Tests\Entity
 * @author   cece4526
 * @license  MIT
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class EnzymeTest extends TestCase
{
    /**
     * Teste les getters et setters de Enzyme.
     *
     * @return void
     */
    public function testGettersAndSetters()
    {
        $entity = new Enzyme();
        $entity->setName('TestEnzyme');
        $this->assertSame('TestEnzyme', $entity->getName());
        $quantite = $this->createMock(QuantiteEnzyme::class);
        $entity->addQuantiteEnzyme($quantite);
        $this->assertTrue($entity->getQuantiteEnzymes()->contains($quantite));
        $entity->removeQuantiteEnzyme($quantite);
        $this->assertFalse($entity->getQuantiteEnzymes()->contains($quantite));
    }
}
