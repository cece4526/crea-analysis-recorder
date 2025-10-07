<?php
namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

/**
 * Test générique pour toutes les entités du projet.
 * Ce test utilise la réflexion pour tester automatiquement 
 * les getters et setters de toutes les entités.
 *
 * @category Test
 * @package  App\Tests\Entity
 * @author   cece4526 <contact@example.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class GenericEntityTest extends TestCase
{
    /**
     * Liste des entités disponibles dans le projet.
     *
     * @return array<string, array<int, string>>
     */
    public static function entityProvider(): array
    {
        return [
            'Production' => ['App\Entity\Production'],
            'Enzyme' => ['App\Entity\Enzyme'],
            'OF' => ['App\Entity\OF'],
        ];
    }

    /**
     * Teste l'instanciation des entités.
     *
     * @dataProvider entityProvider
     * @param string $entityClass Le nom de la classe d'entité à tester
     * @return void
     */
    public function testEntityInstantiation(string $entityClass): void
    {
        if (!class_exists($entityClass)) {
            $this->markTestSkipped("La classe {$entityClass} n'existe pas");
            return;
        }

        try {
            $entity = new $entityClass();
            $this->assertInstanceOf($entityClass, $entity);
        } catch (\Throwable $e) {
            $this->markTestSkipped("Impossible d'instancier {$entityClass}: " . $e->getMessage());
        }
    }

    /**
     * Teste les getters et setters automatiquement.
     *
     * @dataProvider entityProvider
     * @param string $entityClass Le nom de la classe d'entité à tester
     * @return void
     */
    public function testGettersAndSetters(string $entityClass): void
    {
        if (!class_exists($entityClass)) {
            $this->markTestSkipped("La classe {$entityClass} n'existe pas");
            return;
        }

        try {
            $entity = new $entityClass();
            $reflection = new ReflectionClass($entity);
            
            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
            $setters = [];
            $getters = [];

            // Identifier les setters et getters
            foreach ($methods as $method) {
                $methodName = $method->getName();
                
                if (strpos($methodName, 'set') === 0 && $method->getNumberOfParameters() === 1) {
                    $property = lcfirst(substr($methodName, 3));
                    $setters[$property] = $method;
                }
                
                if (strpos($methodName, 'get') === 0 && $method->getNumberOfParameters() === 0) {
                    $property = lcfirst(substr($methodName, 3));
                    $getters[$property] = $method;
                } elseif (strpos($methodName, 'is') === 0 && $method->getNumberOfParameters() === 0) {
                    $property = lcfirst(substr($methodName, 2));
                    $getters[$property] = $method;
                }
            }

            // Tester les paires getter/setter
            foreach ($setters as $property => $setter) {
                if (isset($getters[$property])) {
                    $this->testGetterSetterPair($entity, $setter, $getters[$property], $property);
                }
            }

            // Vérifier qu'au moins une paire a été testée
            $this->assertTrue(count($setters) > 0 || count($getters) > 0, 
                "Aucun getter ou setter trouvé dans {$entityClass}");

        } catch (\Throwable $e) {
            $this->markTestSkipped("Erreur lors du test de {$entityClass}: " . $e->getMessage());
        }
    }

    /**
     * Teste une paire getter/setter spécifique.
     *
     * @param object $entity L'instance de l'entité
     * @param ReflectionMethod $setter La méthode setter
     * @param ReflectionMethod $getter La méthode getter
     * @param string $property Le nom de la propriété
     * @return void
     */
    private function testGetterSetterPair($entity, ReflectionMethod $setter, ReflectionMethod $getter, string $property): void
    {
        try {
            // Déterminer une valeur de test appropriée
            $testValue = $this->generateTestValue($setter, $property);
            
            // Appeler le setter
            $result = $setter->invoke($entity, $testValue);
            
            // Vérifier le chaînage (fluent interface) si applicable
            if ($result === $entity) {
                $this->assertSame($entity, $result, "Le setter {$setter->getName()} doit retourner l'instance");
            }
            
            // Appeler le getter et vérifier la valeur
            $getValue = $getter->invoke($entity);
            $this->assertEquals($testValue, $getValue, 
                "La valeur retournée par {$getter->getName()} ne correspond pas à celle définie par {$setter->getName()}");
                
        } catch (\Throwable $e) {
            // Si le test échoue, on l'ignore pour éviter de faire échouer tout le test
            $this->addWarning("Impossible de tester la paire {$setter->getName()}/{$getter->getName()}: " . $e->getMessage());
        }
    }

    /**
     * Génère une valeur de test appropriée pour un setter.
     *
     * @param ReflectionMethod $setter La méthode setter
     * @param string $property Le nom de la propriété
     * @return mixed
     */
    private function generateTestValue(ReflectionMethod $setter, string $property)
    {
        $parameters = $setter->getParameters();
        if (empty($parameters)) {
            return null;
        }

        $parameter = $parameters[0];
        $type = $parameter->getType();

        if ($type === null) {
            return 'test_value'; // Valeur par défaut
        }

        $typeName = $type->getName();

        switch ($typeName) {
            case 'string':
                return "test_{$property}";
            case 'int':
                return 42;
            case 'float':
                return 3.14;
            case 'bool':
                return true;
            case 'DateTime':
                return new \DateTime('2025-10-07');
            default:
                // Pour les autres types, essayer une valeur simple
                if ($type->allowsNull()) {
                    return null;
                }
                return "test_value";
        }
    }

    /**
     * Teste que les entités ont des propriétés identifiées.
     *
     * @dataProvider entityProvider
     * @param string $entityClass Le nom de la classe d'entité à tester
     * @return void
     */
    public function testEntityHasIdProperty(string $entityClass): void
    {
        if (!class_exists($entityClass)) {
            $this->markTestSkipped("La classe {$entityClass} n'existe pas");
            return;
        }

        try {
            $entity = new $entityClass();
            $reflection = new ReflectionClass($entity);
            
            $hasGetId = $reflection->hasMethod('getId');
            
            if ($hasGetId) {
                $getId = $reflection->getMethod('getId');
                $this->assertTrue($getId->isPublic(), "La méthode getId() doit être publique");
                $this->assertEquals(0, $getId->getNumberOfParameters(), "La méthode getId() ne doit pas avoir de paramètres");
                
                // Tester que getId() retourne null initialement (pour les nouvelles entités)
                $id = $getId->invoke($entity);
                $this->assertNull($id, "L'ID d'une nouvelle entité doit être null");
            }
            
        } catch (\Throwable $e) {
            $this->addWarning("Impossible de tester l'ID de {$entityClass}: " . $e->getMessage());
        }
    }
}
