<?php

namespace App\Tests\Entity;

use App\Entity\SimpleTestEntity;
use PHPUnit\Framework\TestCase;

/**
 * Test unitaire pour l'entité SimpleTestEntity.
 *
 * @category Test
 * @package  App\Tests\Entity
 * @author   cece4526 <contact@example.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class SimpleTestEntityTest extends TestCase
{
    /**
     * Teste les getters et setters de SimpleTestEntity.
     *
     * @return void
     */
    public function testGettersAndSetters()
    {
        $entity = new SimpleTestEntity();

        // Test de l'ID (généré automatiquement, donc null au début)
        $this->assertNull($entity->getId());

        // Test du nom
        $entity->setName('Test Entity');
        $this->assertSame('Test Entity', $entity->getName());

        // Test de la valeur
        $entity->setValue(123);
        $this->assertSame(123, $entity->getValue());

        // Test de setValue avec null
        $entity->setValue(null);
        $this->assertNull($entity->getValue());

        // Test du statut actif
        $this->assertFalse($entity->isActive()); // Valeur par défaut
        $entity->setActive(true);
        $this->assertTrue($entity->isActive());
        $entity->setActive(false);
        $this->assertFalse($entity->isActive());
    }

    /**
     * Teste le chainage des méthodes (fluent interface).
     *
     * @return void
     */
    public function testFluentInterface()
    {
        $entity = new SimpleTestEntity();

        $result = $entity
            ->setName('Fluent Test')
            ->setValue(456)
            ->setActive(true);

        $this->assertSame($entity, $result);
        $this->assertSame('Fluent Test', $entity->getName());
        $this->assertSame(456, $entity->getValue());
        $this->assertTrue($entity->isActive());
    }

    /**
     * Teste les types de données.
     *
     * @return void
     */
    public function testDataTypes()
    {
        $entity = new SimpleTestEntity();

        // Test avec une chaîne vide
        $entity->setName('');
        $this->assertSame('', $entity->getName());

        // Test avec des valeurs limites
        $entity->setValue(0);
        $this->assertSame(0, $entity->getValue());

        $entity->setValue(-1);
        $this->assertSame(-1, $entity->getValue());

        // Test avec une grande valeur
        $entity->setValue(PHP_INT_MAX);
        $this->assertSame(PHP_INT_MAX, $entity->getValue());
    }

    /**
     * Teste l'instanciation de l'entité.
     *
     * @return void
     */
    public function testEntityInstantiation()
    {
        $entity = new SimpleTestEntity();

        $this->assertInstanceOf(SimpleTestEntity::class, $entity);
        $this->assertNull($entity->getId());
        $this->assertNull($entity->getName());
        $this->assertNull($entity->getValue());
        $this->assertFalse($entity->isActive());
    }
}
