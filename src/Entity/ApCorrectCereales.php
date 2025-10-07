<?php

namespace App\Entity;

use App\Repository\ApCorrectCerealesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

/**
 * Entité ApCorrectCereales
 */
#[ORM\Entity(repositoryClass: ApCorrectCerealesRepository::class)]
#[ORM\Table(name: 'ap_correct_cereales')]
class ApCorrectCereales
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(name: 'date', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(name: 'tank', type: Types::INTEGER)]
    private ?int $tank = null;

    #[ORM\Column(name: 'eau_ajouter', type: Types::INTEGER)]
    private ?int $eauAjouter = null;

    #[ORM\Column(name: 'produit_fini', type: Types::INTEGER)]
    private ?int $produitFini = null;

    #[ORM\Column(name: 'es_tank', type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $esTank = null;

    #[ORM\Column(name: 'culot', type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $culot = null;

    #[ORM\Column(name: 'ph', type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $ph = null;

    #[ORM\Column(name: 'densiter', type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $densiter = null;

    #[ORM\Column(name: 'sucre', type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $sucre = null;

    #[ORM\Column(name: 'cryoscopie', type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $cryoscopie = null;

    #[ORM\Column(name: 'initial_pilote', type: Types::STRING, length: 255)]
    private ?string $initialPilote = null;

    #[ORM\OneToOne(targetEntity: OF::class, inversedBy: '_apCorrectCereales', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?OF $_of = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTank(): ?int
    {
        return $this->tank;
    }

    public function setTank(?int $tank): self
    {
        $this->tank = $tank;
        return $this;
    }

    public function getEauAjouter(): ?int
    {
        return $this->eauAjouter;
    }

    public function setEauAjouter(?int $eauAjouter): self
    {
        $this->eauAjouter = $eauAjouter;
        return $this;
    }

    public function getProduitFini(): ?int
    {
        return $this->produitFini;
    }

    public function setProduitFini(?int $produitFini): self
    {
        $this->produitFini = $produitFini;
        return $this;
    }

    public function getEsTank(): ?string
    {
        return $this->esTank;
    }

    public function setEsTank(?string $esTank): self
    {
        $this->esTank = $esTank;
        return $this;
    }

    public function getCulot(): ?string
    {
        return $this->culot;
    }

    public function setCulot(?string $culot): self
    {
        $this->culot = $culot;
        return $this;
    }

    public function getPh(): ?string
    {
        return $this->ph;
    }

    public function setPh(?string $ph): self
    {
        $this->ph = $ph;
        return $this;
    }

    public function getDensiter(): ?string
    {
        return $this->densiter;
    }

    public function setDensiter(?string $densiter): self
    {
        $this->densiter = $densiter;
        return $this;
    }

    public function getSucre(): ?string
    {
        return $this->sucre;
    }

    public function setSucre(?string $sucre): self
    {
        $this->sucre = $sucre;
        return $this;
    }

    public function getCryoscopie(): ?string
    {
        return $this->cryoscopie;
    }

    public function setCryoscopie(?string $cryoscopie): self
    {
        $this->cryoscopie = $cryoscopie;
        return $this;
    }

    public function getInitialPilote(): ?string
    {
        return $this->initialPilote;
    }

    public function setInitialPilote(?string $initialPilote): self
    {
        $this->initialPilote = $initialPilote;
        return $this;
    }

    public function getOf(): ?OF
    {
        return $this->_of;
    }

    public function setOf(?OF $of): self
    {
        $this->_of = $of;
        return $this;
    }
}
