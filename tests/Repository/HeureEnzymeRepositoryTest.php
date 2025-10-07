<?php
namespace App\Tests\Repository;

use App\Entity\HeureEnzyme;
use App\Repository\HeureEnzymeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HeureEnzymeRepositoryTest extends KernelTestCase
{
    public function testFindAllReturnsArray(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(HeureEnzymeRepository::class);
        $result = $repo->findAll();
        $this->assertIsArray($result);
    }

    public function testFindByIdReturnsNullOrEntity(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(HeureEnzymeRepository::class);
        $entity = $repo->find(1);
        $this->assertTrue($entity === null || $entity instanceof HeureEnzyme);
    }
}
