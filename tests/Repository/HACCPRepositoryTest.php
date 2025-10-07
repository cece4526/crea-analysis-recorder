<?php
namespace App\Tests\Repository;

use App\Entity\HACCP;
use App\Repository\HACCPRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HACCPRepositoryTest extends KernelTestCase
{
    public function testFindAllReturnsArray(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(HACCPRepository::class);
        $result = $repo->findAll();
        $this->assertIsArray($result);
    }

    public function testFindByIdReturnsNullOrEntity(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(HACCPRepository::class);
        $entity = $repo->find(1);
        $this->assertTrue($entity === null || $entity instanceof HACCP);
    }
}
