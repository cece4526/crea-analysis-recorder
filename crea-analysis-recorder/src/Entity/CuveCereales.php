<?php

namespace App\Entity;

use App\Repository\CuveCerealesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité CuveCereales
 *
 * @ORM\Entity(repositoryClass=CuveCerealesRepository::class)
 */
class CuveCereales
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $cuve = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $debit_enzyme = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $temperature_hydrolise = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $quantite_enzyme2 = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $matiere = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $control_verre = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $initial_pilote = null;

    // Getters et setters à générer ici
}
