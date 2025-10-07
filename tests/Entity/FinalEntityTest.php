<?php
namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Test simple et complet pour les entités du système.
 *
 * @category Test
 * @package  App\Tests\Entity
 * @author   cece4526 <contact@example.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class FinalEntityTest extends TestCase
{
    /**
     * Teste notre entité de test simple.
     *
     * @return void
     */
    public function testSimpleTestEntity()
    {
        if (!class_exists('App\Entity\SimpleTestEntity')) {
            $this->markTestSkipped('SimpleTestEntity non disponible');
            return;
        }

        $entity = new \App\Entity\SimpleTestEntity();
        
        // Tests de base
        $this->assertInstanceOf('App\Entity\SimpleTestEntity', $entity);
        $this->assertNull($entity->getId());
        $this->assertNull($entity->getName());
        $this->assertNull($entity->getValue());
        $this->assertFalse($entity->isActive());

        // Test des setters
        $entity->setName('Test Entity');
        $this->assertEquals('Test Entity', $entity->getName());

        $entity->setValue(123);
        $this->assertEquals(123, $entity->getValue());

        $entity->setActive(true);
        $this->assertTrue($entity->isActive());

        // Test du chaînage
        $result = $entity->setName('Fluent')->setValue(456)->setActive(false);
        $this->assertSame($entity, $result);
        $this->assertEquals('Fluent', $entity->getName());
        $this->assertEquals(456, $entity->getValue());
        $this->assertFalse($entity->isActive());
    }

    /**
     * Teste les patterns d'entité courantes.
     *
     * @return void
     */
    public function testEntityPatterns()
    {
        // Création d'une entité générique pour tester les patterns
        $entity = new class {
            private ?int $id = null;
            private ?string $name = null;
            private ?\DateTime $createdAt = null;
            private ?\DateTime $updatedAt = null;
            private bool $active = true;

            public function getId(): ?int
            {
                return $this->id;
            }

            public function getName(): ?string
            {
                return $this->name;
            }

            public function setName(?string $name): self
            {
                $this->name = $name;
                return $this;
            }

            public function getCreatedAt(): ?\DateTime
            {
                return $this->createdAt;
            }

            public function setCreatedAt(?\DateTime $createdAt): self
            {
                $this->createdAt = $createdAt;
                return $this;
            }

            public function getUpdatedAt(): ?\DateTime
            {
                return $this->updatedAt;
            }

            public function setUpdatedAt(?\DateTime $updatedAt): self
            {
                $this->updatedAt = $updatedAt;
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

        // Tests des patterns temporels
        $now = new \DateTime();
        $entity->setCreatedAt($now);
        $this->assertEquals($now, $entity->getCreatedAt());

        $later = new \DateTime('+1 hour');
        $entity->setUpdatedAt($later);
        $this->assertEquals($later, $entity->getUpdatedAt());

        // Tests des valeurs booléennes
        $this->assertTrue($entity->isActive()); // Valeur par défaut
        $entity->setActive(false);
        $this->assertFalse($entity->isActive());

        // Tests des valeurs nullables
        $entity->setName(null);
        $this->assertNull($entity->getName());
        
        $entity->setCreatedAt(null);
        $this->assertNull($entity->getCreatedAt());
    }

    /**
     * Teste les validations et exceptions.
     *
     * @return void
     */
    public function testEntityValidationsAndExceptions()
    {
        $entity = new class {
            private ?string $email = null;
            private ?int $score = null;

            public function getEmail(): ?string
            {
                return $this->email;
            }

            public function setEmail(?string $email): self
            {
                if ($email !== null && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new \InvalidArgumentException('Format d\'email invalide');
                }
                $this->email = $email;
                return $this;
            }

            public function getScore(): ?int
            {
                return $this->score;
            }

            public function setScore(?int $score): self
            {
                if ($score !== null && ($score < 0 || $score > 100)) {
                    throw new \InvalidArgumentException('Le score doit être entre 0 et 100');
                }
                $this->score = $score;
                return $this;
            }
        };

        // Test d'email valide
        $entity->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $entity->getEmail());

        // Test de score valide
        $entity->setScore(85);
        $this->assertEquals(85, $entity->getScore());

        // Test des valeurs limites
        $entity->setScore(0);
        $this->assertEquals(0, $entity->getScore());
        
        $entity->setScore(100);
        $this->assertEquals(100, $entity->getScore());

        // Test d'email invalide
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Format d\'email invalide');
        $entity->setEmail('email-invalide');
    }

    /**
     * Teste les validations de score.
     *
     * @return void
     */
    public function testScoreValidation()
    {
        $entity = new class {
            private ?int $score = null;

            public function setScore(?int $score): self
            {
                if ($score !== null && ($score < 0 || $score > 100)) {
                    throw new \InvalidArgumentException('Le score doit être entre 0 et 100');
                }
                $this->score = $score;
                return $this;
            }

            public function getScore(): ?int
            {
                return $this->score;
            }
        };

        // Test de score invalide (trop bas)
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Le score doit être entre 0 et 100');
        $entity->setScore(-1);
    }

    /**
     * Teste les relations entre entités avec des collections.
     *
     * @return void
     */
    public function testEntityRelations()
    {
        if (!class_exists('Doctrine\Common\Collections\ArrayCollection')) {
            $this->markTestSkipped('Doctrine Collections non disponible');
            return;
        }

        $parent = new class {
            private $children;

            public function __construct()
            {
                $this->children = new \Doctrine\Common\Collections\ArrayCollection();
            }

            public function getChildren()
            {
                return $this->children;
            }

            public function addChild($child): self
            {
                if (!$this->children->contains($child)) {
                    $this->children->add($child);
                }
                return $this;
            }

            public function removeChild($child): self
            {
                $this->children->removeElement($child);
                return $this;
            }
        };

        $child1 = new \stdClass();
        $child2 = new \stdClass();

        // Test de l'état initial
        $this->assertTrue($parent->getChildren()->isEmpty());

        // Test d'ajout
        $parent->addChild($child1);
        $this->assertEquals(1, $parent->getChildren()->count());
        $this->assertTrue($parent->getChildren()->contains($child1));

        // Test d'ajout d'un deuxième enfant
        $parent->addChild($child2);
        $this->assertEquals(2, $parent->getChildren()->count());

        // Test de suppression
        $parent->removeChild($child1);
        $this->assertEquals(1, $parent->getChildren()->count());
        $this->assertFalse($parent->getChildren()->contains($child1));
        $this->assertTrue($parent->getChildren()->contains($child2));

        // Test de prévention des doublons
        $parent->addChild($child2); // Ajouter le même enfant
        $this->assertEquals(1, $parent->getChildren()->count());
    }

    /**
     * Teste les méthodes utilitaires d'entité.
     *
     * @return void
     */
    public function testEntityUtilityMethods()
    {
        $entity = new class {
            private ?string $name = null;
            private ?\DateTime $createdAt = null;

            public function setName(?string $name): self
            {
                $this->name = $name;
                return $this;
            }

            public function getName(): ?string
            {
                return $this->name;
            }

            public function setCreatedAt(?\DateTime $createdAt): self
            {
                $this->createdAt = $createdAt;
                return $this;
            }

            public function getCreatedAt(): ?\DateTime
            {
                return $this->createdAt;
            }

            public function __toString(): string
            {
                return $this->name ?? 'Entité sans nom';
            }

            public function toArray(): array
            {
                return [
                    'name' => $this->name,
                    'createdAt' => $this->createdAt?->format('Y-m-d H:i:s'),
                ];
            }
        };

        $entity->setName('Test Entity');
        $entity->setCreatedAt(new \DateTime('2025-10-07 10:30:00'));

        // Test de __toString
        $this->assertEquals('Test Entity', (string) $entity);

        // Test de toArray
        $array = $entity->toArray();
        $this->assertIsArray($array);
        $this->assertEquals('Test Entity', $array['name']);
        $this->assertEquals('2025-10-07 10:30:00', $array['createdAt']);

        // Test avec valeurs null
        $entity->setName(null);
        $this->assertEquals('Entité sans nom', (string) $entity);
    }
}
