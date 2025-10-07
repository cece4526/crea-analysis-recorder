<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Test unitaire générique pour valider les concepts d'entités.
 *
 * @category Test
 * @package  App\Tests\Entity
 * @author   cece4526 <contact@example.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class EntityValidationTest extends TestCase
{
    /**
     * Teste la structure d'une entité basique.
     *
     * @return void
     */
    public function testBasicEntityStructure()
    {
        // Créer une classe d'entité simple à la volée pour les tests
        $entity = new class {
            private ?int $id = null;
            private ?string $name = null;
            private bool $active = false;

            public function getId(): ?int
            {
                return $this->id;
            }

            public function getName(): ?string
            {
                return $this->name;
            }

            public function setName(string $name): self
            {
                $this->name = $name;
                return $this;
            }

            public function isActive(): bool
            {
                return $this->active;
            }

            public function setActive(bool $active): self
            {
                $this->active = $active;
                return $this;
            }
        };

        // Test de l'état initial
        $this->assertNull($entity->getId());
        $this->assertNull($entity->getName());
        $this->assertFalse($entity->isActive());

        // Test des setters
        $entity->setName('Test Entity');
        $this->assertEquals('Test Entity', $entity->getName());

        $entity->setActive(true);
        $this->assertTrue($entity->isActive());

        // Test du chaînage
        $result = $entity->setName('Chained')->setActive(false);
        $this->assertSame($entity, $result);
        $this->assertEquals('Chained', $entity->getName());
        $this->assertFalse($entity->isActive());
    }

    /**
     * Teste les types de données couramment utilisés dans les entités.
     *
     * @return void
     */
    public function testEntityDataTypes()
    {
        $entity = new class {
            private ?int $intValue = null;
            private ?string $stringValue = null;
            private ?float $floatValue = null;
            private ?bool $boolValue = null;
            private ?\DateTime $dateValue = null;

            public function getIntValue(): ?int
            {
                return $this->intValue;
            }

            public function setIntValue(?int $value): self
            {
                $this->intValue = $value;
                return $this;
            }

            public function getStringValue(): ?string
            {
                return $this->stringValue;
            }

            public function setStringValue(?string $value): self
            {
                $this->stringValue = $value;
                return $this;
            }

            public function getFloatValue(): ?float
            {
                return $this->floatValue;
            }

            public function setFloatValue(?float $value): self
            {
                $this->floatValue = $value;
                return $this;
            }

            public function getBoolValue(): ?bool
            {
                return $this->boolValue;
            }

            public function setBoolValue(?bool $value): self
            {
                $this->boolValue = $value;
                return $this;
            }

            public function getDateValue(): ?\DateTime
            {
                return $this->dateValue;
            }

            public function setDateValue(?\DateTime $value): self
            {
                $this->dateValue = $value;
                return $this;
            }
        };

        // Test des entiers
        $entity->setIntValue(123);
        $this->assertIsInt($entity->getIntValue());
        $this->assertEquals(123, $entity->getIntValue());

        // Test des chaînes
        $entity->setStringValue('test string');
        $this->assertIsString($entity->getStringValue());
        $this->assertEquals('test string', $entity->getStringValue());

        // Test des flottants
        $entity->setFloatValue(123.45);
        $this->assertIsFloat($entity->getFloatValue());
        $this->assertEquals(123.45, $entity->getFloatValue());

        // Test des booléens
        $entity->setBoolValue(true);
        $this->assertIsBool($entity->getBoolValue());
        $this->assertTrue($entity->getBoolValue());

        // Test des dates
        $date = new \DateTime('2025-10-07');
        $entity->setDateValue($date);
        $this->assertInstanceOf(\DateTime::class, $entity->getDateValue());
        $this->assertEquals($date, $entity->getDateValue());

        // Test des valeurs null
        $entity->setIntValue(null);
        $this->assertNull($entity->getIntValue());
    }

    /**
     * Teste les collections avec ArrayCollection.
     *
     * @return void
     */
    public function testEntityCollections()
    {
        if (!class_exists('Doctrine\Common\Collections\ArrayCollection')) {
            $this->markTestSkipped('Doctrine Collections not available');
            return;
        }

        $entity = new class {
            private $items;

            public function __construct()
            {
                $this->items = new \Doctrine\Common\Collections\ArrayCollection();
            }

            public function getItems()
            {
                return $this->items;
            }

            public function addItem($item): self
            {
                if (!$this->items->contains($item)) {
                    $this->items->add($item);
                }
                return $this;
            }

            public function removeItem($item): self
            {
                $this->items->removeElement($item);
                return $this;
            }
        };

        // Test de l'état initial
        $this->assertTrue($entity->getItems()->isEmpty());
        $this->assertEquals(0, $entity->getItems()->count());

        // Test d'ajout d'éléments
        $item1 = 'item1';
        $item2 = 'item2';

        $entity->addItem($item1);
        $this->assertFalse($entity->getItems()->isEmpty());
        $this->assertEquals(1, $entity->getItems()->count());
        $this->assertTrue($entity->getItems()->contains($item1));

        $entity->addItem($item2);
        $this->assertEquals(2, $entity->getItems()->count());
        $this->assertTrue($entity->getItems()->contains($item2));

        // Test de suppression
        $entity->removeItem($item1);
        $this->assertEquals(1, $entity->getItems()->count());
        $this->assertFalse($entity->getItems()->contains($item1));
        $this->assertTrue($entity->getItems()->contains($item2));

        // Test de prévention des doublons
        $entity->addItem($item2); // Ajouter le même élément
        $this->assertEquals(1, $entity->getItems()->count()); // Pas de doublon
    }

    /**
     * Teste les validations basiques d'entité.
     *
     * @return void
     */
    public function testEntityValidation()
    {
        $entity = new class {
            private ?string $email = null;
            private ?int $age = null;

            public function getEmail(): ?string
            {
                return $this->email;
            }

            public function setEmail(?string $email): self
            {
                // Validation basique d'email
                if ($email !== null && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new \InvalidArgumentException('Invalid email format');
                }
                $this->email = $email;
                return $this;
            }

            public function getAge(): ?int
            {
                return $this->age;
            }

            public function setAge(?int $age): self
            {
                // Validation basique d'âge
                if ($age !== null && ($age < 0 || $age > 150)) {
                    throw new \InvalidArgumentException('Age must be between 0 and 150');
                }
                $this->age = $age;
                return $this;
            }
        };

        // Test d'email valide
        $entity->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $entity->getEmail());

        // Test d'email invalide
        $this->expectException(\InvalidArgumentException::class);
        $entity->setEmail('invalid-email');
    }

    /**
     * Teste les validations d'âge.
     *
     * @return void
     */
    public function testAgeValidation()
    {
        $entity = new class {
            private ?int $age = null;

            public function setAge(?int $age): self
            {
                if ($age !== null && ($age < 0 || $age > 150)) {
                    throw new \InvalidArgumentException('Age must be between 0 and 150');
                }
                $this->age = $age;
                return $this;
            }

            public function getAge(): ?int
            {
                return $this->age;
            }
        };

        // Test d'âge valide
        $entity->setAge(25);
        $this->assertEquals(25, $entity->getAge());

        // Test d'âge limite (0)
        $entity->setAge(0);
        $this->assertEquals(0, $entity->getAge());

        // Test d'âge limite (150)
        $entity->setAge(150);
        $this->assertEquals(150, $entity->getAge());

        // Test d'âge invalide (négatif)
        $this->expectException(\InvalidArgumentException::class);
        $entity->setAge(-1);
    }
}
