<?php
namespace App\Tests\Repository;

use App\Entity\Production;
use App\Repository\ProductionRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductionRepositoryTest extends KernelTestCase
{
    public function testFindAllReturnsArray(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(ProductionRepository::class);
        $result = $repo->findAll();
        $this->assertIsArray($result);
    }

    public function testFindByIdReturnsNullOrEntity(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(ProductionRepository::class);
        $entity = $repo->find(1);
        $this->assertTrue($entity === null || $entity instanceof Production);
    }
}
