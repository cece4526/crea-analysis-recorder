<?php
namespace App\Tests\Repository;

use App\Entity\AnalyseSoja;
use App\Repository\AnalyseSojaRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AnalyseSojaRepositoryTest extends KernelTestCase
{
    public function testFindAllReturnsArray(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(AnalyseSojaRepository::class);
        $result = $repo->findAll();
        $this->assertIsArray($result);
    }

    public function testFindByIdReturnsNullOrEntity(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(AnalyseSojaRepository::class);
        $entity = $repo->find(1);
        $this->assertTrue($entity === null || $entity instanceof AnalyseSoja);
    }
}
