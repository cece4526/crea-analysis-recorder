<?php
namespace App\Tests\Repository;

use App\Entity\QuantiteEnzyme;
use App\Repository\QuantiteEnzymeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class QuantiteEnzymeRepositoryTest extends KernelTestCase
{
    public function testFindAllReturnsArray(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(QuantiteEnzymeRepository::class);
        $result = $repo->findAll();
        $this->assertIsArray($result);
    }

    public function testFindByIdReturnsNullOrEntity(): void
    {
        self::bootKernel();
        $repo = static::getContainer()->get(QuantiteEnzymeRepository::class);
        $entity = $repo->find(1);
        $this->assertTrue($entity === null || $entity instanceof QuantiteEnzyme);
    }
}
