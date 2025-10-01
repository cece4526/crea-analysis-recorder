<?php
namespace App\Tests\Repository;

use App\Entity\ApCorrectSoja;
use App\Repository\ApCorrectSojaRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ApCorrectSojaRepositoryTest extends KernelTestCase
{
    public function testFindAllReturnsArray(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(ApCorrectSojaRepository::class);
        $result = $repo->findAll();
        $this->assertIsArray($result);
    }

    public function testFindByIdReturnsNullOrEntity(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(ApCorrectSojaRepository::class);
        $entity = $repo->find(1);
        $this->assertTrue($entity === null || $entity instanceof ApCorrectSoja);
    }
}
