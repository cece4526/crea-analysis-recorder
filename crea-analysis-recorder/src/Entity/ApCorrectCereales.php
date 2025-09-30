<?php

namespace App\Entity;

use App\Repository\ApCorrectCerealesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApCorrectCerealesRepository::class)
 */
class ApCorrectCereales
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
    private ?int $eauAjouter = null;

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
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private ?string $culot = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private ?string $ph = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private ?string $densiter = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private ?string $proteine = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $initialPilote = null;

    // Getters et setters à générer
}
