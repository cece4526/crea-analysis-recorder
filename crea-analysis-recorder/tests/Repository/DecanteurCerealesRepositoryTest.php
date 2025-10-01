<?php
namespace App\Tests\Repository;

use App\Entity\DecanteurCereales;
use App\Repository\DecanteurCerealesRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DecanteurCerealesRepositoryTest extends KernelTestCase
{
    public function testFindAllReturnsArray(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(DecanteurCerealesRepository::class);
        $result = $repo->findAll();
        $this->assertIsArray($result);
    }

    public function testFindByIdReturnsNullOrEntity(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(DecanteurCerealesRepository::class);
        $entity = $repo->find(1);
        $this->assertTrue($entity === null || $entity instanceof DecanteurCereales);
    }
}
