<?php
namespace App\Tests\Repository;

use App\Entity\AvCorrectCereales;
use App\Repository\AvCorrectCerealesRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AvCorrectCerealesRepositoryTest extends KernelTestCase
{
    public function testFindAllReturnsArray(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(AvCorrectCerealesRepository::class);
        $result = $repo->findAll();
        $this->assertIsArray($result);
    }

    public function testFindByIdReturnsNullOrEntity(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(AvCorrectCerealesRepository::class);
        $entity = $repo->find(1);
        $this->assertTrue($entity === null || $entity instanceof AvCorrectCereales);
    }
}
