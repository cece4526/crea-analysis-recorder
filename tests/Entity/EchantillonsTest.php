<?php
namespace App\Tests\Entity;

use App\Entity\Echantillons;
use App\Entity\Okara;
use PHPUnit\Framework\TestCase;

/**
 * Test unitaire pour l'entité Echantillons.
 *
 * @category Test
 * @package  App\Tests\Entity
 * @author   cece4526 <contact@example.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class EchantillonsTest extends TestCase
{
    /**
     * Teste tous les getters et setters de l'entité Echantillons.
     *
     * @return void
     */
    public function testGettersAndSetters()
    {
        $entity = new Echantillons();
        $this->assertNull($entity->getId());

        $entity->setPoids0('1.1');
        $this->assertSame('1.1', $entity->getPoids0());

        $entity->setPoids1('2.2');
        $this->assertSame('2.2', $entity->getPoids1());

        $entity->setPoids2('3.3');
        $this->assertSame('3.3', $entity->getPoids2());

        $entity->setExtraitSec('4.4');
        $this->assertSame('4.4', $entity->getExtraitSec());

        $okara = $this->createMock(Okara::class);
        $entity->setOkara($okara);
        $this->assertSame($okara, $entity->getOkara());
    }
}
