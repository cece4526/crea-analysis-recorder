<?php

namespace App\Entity;

use App\Repository\ApCorrectSojaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApCorrectSojaRepository::class)
 */
class ApCorrectSoja
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

    /**
     * @ORM\OneToOne(targetEntity=OF::class, inversedBy="apCorrectSoja", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?OF $of = null;

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

    public function getMatiere(): ?int
    {
        return $this->matiere;
    }

    public function setMatiere(?int $matiere): self
    {
        $this->matiere = $matiere;
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

    public function getProteine(): ?string
    {
        return $this->proteine;
    }

    public function setProteine(?string $proteine): self
    {
        $this->proteine = $proteine;
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
        return $this->of;
    }

    public function setOf(?OF $of): self
    {
        $this->of = $of;
        return $this;
    }
}





