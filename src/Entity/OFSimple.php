<?php

namespace App\Entity;

use App\Repository\OFRepository;
use App\Entity\Production;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

/**
 * Entité OF simplifiée - Version pour tests
 */
#[ORM\Entity(repositoryClass: OFRepository::class)]
#[ORM\Table(name: '`of`')]
class OFSimple
{
    /**
     * Identifiant unique
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    /**
     * Nom de l'OF
     */
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    /**
     * Numéro de l'OF
     */
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $numero = null;

    /**
     * Nature du produit
     */
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $nature = null;

    /**
     * Date de l'OF
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    /**
     * Production associée à cet OF
     */
    #[ORM\ManyToOne(targetEntity: Production::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Production $production = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(?int $numero): self
    {
        $this->numero = $numero;
        return $this;
    }

    public function getNature(): ?string
    {
        return $this->nature;
    }

    public function setNature(?string $nature): self
    {
        $this->nature = $nature;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getProduction(): ?Production
    {
        return $this->production;
    }

    public function setProduction(?Production $production): self
    {
        $this->production = $production;
        return $this;
    }
}
