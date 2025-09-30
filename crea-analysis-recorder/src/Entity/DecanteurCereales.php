<?php

namespace App\Entity;

use App\Repository\DecanteurCerealesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité DecanteurCereales
 *
 * @ORM\Entity(repositoryClass=DecanteurCerealesRepository::class)
 */
class DecanteurCereales
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
    private ?string $es_av_decan = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $es_ap_decan = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $vitesse_diff = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $variponds = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $couple = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $contre_pression = null;

    // Getters et setters à générer ici
}
