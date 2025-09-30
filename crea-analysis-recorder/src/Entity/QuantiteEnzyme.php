<?php

namespace App\Entity;

use App\Repository\QuantiteEnzymeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Enzyme;
use App\Entity\OF;

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
    private ?int $_id = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_pourcentage = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_quantite = null;

    /**
     * @ORM\ManyToMany(targetEntity=Enzyme::class, mappedBy="quantiteEnzymes")
     */
    private Collection $_enzymes;

    /**
     * @ORM\ManyToOne(targetEntity=OF::class, inversedBy="quantiteEnzymes")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?OF $_of = null;

    public function __construct()
    {
    $this->_enzymes = new ArrayCollection();
    }

    /**
     * @return Collection<int, Enzyme>
     */
    public function getEnzymes(): Collection
    {
        return $this->_enzymes;
    }

    public function addEnzyme(Enzyme $enzyme): self
    {
        if (!$this->_enzymes->contains($enzyme)) {
            $this->_enzymes[] = $enzyme;
        }
        return $this;
    }

    public function removeEnzyme(Enzyme $enzyme): self
    {
        $this->_enzymes->removeElement($enzyme);
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

    public function getId(): ?int
    {
        return $this->_id;
    }

    public function getPourcentage(): ?string
    {
        return $this->_pourcentage;
    }

    public function setPourcentage(?string $pourcentage): self
    {
        $this->_pourcentage = $pourcentage;
        return $this;
    }

    public function getQuantite(): ?string
    {
        return $this->_quantite;
    }

    public function setQuantite(?string $quantite): self
    {
        $this->_quantite = $quantite;
        return $this;
    }
}
