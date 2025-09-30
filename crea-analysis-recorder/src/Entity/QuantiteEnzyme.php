<?php

namespace App\Entity;

use App\Repository\QuantiteEnzymeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité QuantiteEnzyme
 *
 * @ORM\Entity(repositoryClass=QuantiteEnzymeRepository::class)
 */
class QuantiteEnzyme
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $pourcentage = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $quantite = null;

    // Getters et setters à générer ici
}
