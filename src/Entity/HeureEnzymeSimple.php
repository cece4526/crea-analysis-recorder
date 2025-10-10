<?php

namespace App\Entity;

use App\Repository\HeureEnzymeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

/**
 * Entité HeureEnzymeSimple - Version simplifiée de HeureEnzyme
 * Liée à une cuve hydrolyse spécifique et un OF
 */
#[ORM\Entity(repositoryClass: HeureEnzymeRepository::class)]
#[ORM\Table(name: 'heure_enzyme')]
class HeureEnzymeSimple
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heure = null;

    #[ORM\Column(name: 'of_id', type: Types::INTEGER, nullable: true)]
    private ?int $ofId = null;

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(name: 'cuve_cereales_id', type: Types::INTEGER, nullable: true)]
    private ?int $cuveCerealesId = null;

    // Relation avec CuveCereales
    #[ORM\ManyToOne(targetEntity: CuveCereales::class)]
    #[ORM\JoinColumn(name: 'cuve_cereales_id', referencedColumnName: 'id')]
    private ?CuveCereales $cuveCereales = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): static
    {
        $this->heure = $heure;
        return $this;
    }

    public function getOfId(): ?int
    {
        return $this->ofId;
    }

    public function setOfId(?int $ofId): static
    {
        $this->ofId = $ofId;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCuveCerealesId(): ?int
    {
        return $this->cuveCerealesId;
    }

    public function setCuveCerealesId(?int $cuveCerealesId): static
    {
        $this->cuveCerealesId = $cuveCerealesId;
        return $this;
    }

    public function getCuveCereales(): ?CuveCereales
    {
        return $this->cuveCereales;
    }

    public function setCuveCereales(?CuveCereales $cuveCereales): static
    {
        $this->cuveCereales = $cuveCereales;
        return $this;
    }
}
