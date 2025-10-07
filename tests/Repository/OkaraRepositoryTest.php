<?php
namespace App\Tests\Repository;

use App\Entity\Okara;
use App\Repository\OkaraRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OkaraRepositoryTest extends KernelTestCase
{
    public function testFindAllReturnsArray(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(OkaraRepository::class);
        $result = $repo->findAll();
        $this->assertIsArray($result);
    }

    public function testFindByIdReturnsNullOrEntity(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(OkaraRepository::class);
        $entity = $repo->find(1);
        $this->assertTrue($entity === null || $entity instanceof Okara);
    }
}
