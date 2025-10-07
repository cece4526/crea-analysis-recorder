<?php
namespace App\Tests\Repository;

use App\Entity\AvCorrectSoja;
use App\Repository\AvCorrectSojaRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AvCorrectSojaRepositoryTest extends KernelTestCase
{
    public function testFindAllReturnsArray(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(AvCorrectSojaRepository::class);
        $result = $repo->findAll();
        $this->assertIsArray($result);
    }

    public function testFindByIdReturnsNullOrEntity(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(AvCorrectSojaRepository::class);
        $entity = $repo->find(1);
        $this->assertTrue($entity === null || $entity instanceof AvCorrectSoja);
    }
}
