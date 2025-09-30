<?php

namespace App\Entity;

use App\Repository\AvCorrectSojaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AvCorrectSojaRepository::class)
 */
class AvCorrectSoja
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $date = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $tank = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $eau = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $matiere = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $produitFini = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private ?string $esTank = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $initialPilote = null;

    /**
     * @ORM\OneToOne(targetEntity=OF::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?OF $_of = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $proteine = null;

    // Getters et setters à générer
}
