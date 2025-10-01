<?php

namespace App\Tests\Entity;

use App\Entity\ApCorrectSoja;
use App\Entity\OF;
use PHPUnit\Framework\TestCase;

class ApCorrectSojaTest extends TestCase
{
    public function testSettersAndGetters(): void
    {
        $apCorrectSoja = new ApCorrectSoja();
        $date = new \DateTimeImmutable('2025-09-30 12:00:00');
        $of = $this->createMock(OF::class);

        $apCorrectSoja->setDate($date);
        $apCorrectSoja->setTank(5);
        $apCorrectSoja->setEauAjouter(10);
        $apCorrectSoja->setMatiere(20);
        $apCorrectSoja->setProduitFini(30);
        $apCorrectSoja->setEsTank('12.34');
        $apCorrectSoja->setCulot('2.50');
        $apCorrectSoja->setPh('6.8');
        $apCorrectSoja->setDensiter('1.05');
        $apCorrectSoja->setProteine('45.2');
        $apCorrectSoja->setInitialPilote('AB');
        $apCorrectSoja->setOf($of);

        $this->assertSame($date, $apCorrectSoja->getDate());
        $this->assertSame(5, $apCorrectSoja->getTank());
        $this->assertSame(10, $apCorrectSoja->getEauAjouter());
        $this->assertSame(20, $apCorrectSoja->getMatiere());
        $this->assertSame(30, $apCorrectSoja->getProduitFini());
        $this->assertSame('12.34', $apCorrectSoja->getEsTank());
        $this->assertSame('2.50', $apCorrectSoja->getCulot());
        $this->assertSame('6.8', $apCorrectSoja->getPh());
        $this->assertSame('1.05', $apCorrectSoja->getDensiter());
        $this->assertSame('45.2', $apCorrectSoja->getProteine());
        $this->assertSame('AB', $apCorrectSoja->getInitialPilote());
        $this->assertSame($of, $apCorrectSoja->getOf());
    }
}
