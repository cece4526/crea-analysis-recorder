<?php
namespace App\Tests\Repository;

use App\Entity\CuveCereales;
use App\Repository\CuveCerealesRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CuveCerealesRepositoryTest extends KernelTestCase
{
    public function testFindAllReturnsArray(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(CuveCerealesRepository::class);
        $result = $repo->findAll();
        $this->assertIsArray($result);
    }

    public function testFindByIdReturnsNullOrEntity(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(CuveCerealesRepository::class);
        $entity = $repo->find(1);
        $this->assertTrue($entity === null || $entity instanceof CuveCereales);
    }
}
