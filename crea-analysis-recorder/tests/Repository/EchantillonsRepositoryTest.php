<?php
namespace App\Tests\Repository;

use App\Entity\Echantillons;
use App\Repository\EchantillonsRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EchantillonsRepositoryTest extends KernelTestCase
{
    public function testFindAllReturnsArray(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(EchantillonsRepository::class);
        $result = $repo->findAll();
        $this->assertIsArray($result);
    }

    public function testFindByIdReturnsNullOrEntity(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(EchantillonsRepository::class);
        $entity = $repo->find(1);
        $this->assertTrue($entity === null || $entity instanceof Echantillons);
    }
}
