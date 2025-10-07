<?php
namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Test unitaire simple pour vérifier la structure de base.
 *
 * @category Test
 * @package  App\Tests\Entity
 * @author   cece4526
 * @license  MIT
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class BaseEntityTest extends TestCase
{
    /**
     * Teste que PHPUnit fonctionne correctement.
     *
     * @return void
     */
    public function testPhpUnitIsWorking()
    {
        $this->assertTrue(true);
        $this->assertSame(1, 1);
        $this->assertNotNull('test');
    }

    /**
     * Teste les types de base PHP.
     *
     * @return void
     */
    public function testBasicPhpTypes()
    {
        $string = 'test';
        $integer = 123;
        $float = 45.67;
        $boolean = true;
        $array = ['a', 'b', 'c'];

        $this->assertIsString($string);
        $this->assertIsInt($integer);
        $this->assertIsFloat($float);
        $this->assertIsBool($boolean);
        $this->assertIsArray($array);
    }

    /**
     * Teste les collections Doctrine.
     *
     * @return void
     */
    public function testDoctrineCollections()
    {
        // Test de base avec ArrayCollection
        if (class_exists('Doctrine\Common\Collections\ArrayCollection')) {
            $collection = new \Doctrine\Common\Collections\ArrayCollection();
            $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $collection);
            $this->assertTrue($collection->isEmpty());
            
            $collection->add('test');
            $this->assertFalse($collection->isEmpty());
            $this->assertTrue($collection->contains('test'));
            $this->assertEquals(1, $collection->count());
        } else {
            $this->markTestSkipped('Doctrine Collections not available');
        }
    }
}
