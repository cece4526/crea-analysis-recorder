<?php

namespace App\Entity;

use App\Repository\EnzymeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité Enzyme
 *
 * @ORM\Entity(repositoryClass=EnzymeRepository::class)
 */
class Enzyme
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = null;

    // Getters et setters à générer ici
}
