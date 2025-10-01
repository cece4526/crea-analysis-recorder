<?php
namespace App\Tests\Repository;

use App\Entity\OF;
use App\Repository\OFRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OFRepositoryTest extends KernelTestCase
{
    public function testFindAllReturnsArray(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(OFRepository::class);
        $result = $repo->findAll();
        $this->assertIsArray($result);
    }

    public function testFindByIdReturnsNullOrEntity(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(OFRepository::class);
        $entity = $repo->find(1);
        $this->assertTrue($entity === null || $entity instanceof OF);
    }
}
