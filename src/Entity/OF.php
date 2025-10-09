<?php

namespace App\Entity;

use App\Repository\OFRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Entité OF simplifiée pour résoudre les problèmes de relations
 */
#[ORM\Entity(repositoryClass: OFRepository::class)]
#[ORM\Table(name: 'ordre_fabrication')]
class OF
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(name: 'numero', type: Types::INTEGER)]
    private ?int $numero = null;

    #[ORM\Column(name: 'date', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(name: 'produit', type: Types::STRING, length: 255, nullable: true)]
    private ?string $produit = null;

    #[ORM\Column(name: 'quantite', type: Types::STRING, length: 255, nullable: true)]
    private ?string $quantite = null;

    #[ORM\Column(name: 'statut', type: Types::STRING, length: 50)]
    private ?string $statut = 'en_attente';

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(name: 'updated_at', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * Collection des cuves céréales
     */
    #[ORM\OneToMany(targetEntity: CuveCereales::class, mappedBy: 'of', cascade: ['persist', 'remove'])]
    private Collection $_cuveCereales;

    public function __construct()
    {
        $this->_cuveCereales = new ArrayCollection();
        
        // Initialiser les dates automatiquement
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getProduit(): ?string
    {
        return $this->produit;
    }

    public function setProduit(?string $produit): self
    {
        $this->produit = $produit;
        return $this;
    }

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(?string $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return Collection<int, CuveCereales>
     */
    public function getCuveCereales(): Collection
    {
        return $this->_cuveCereales;
    }

    public function addCuveCereale(CuveCereales $cuveCereale): self
    {
        if (!$this->_cuveCereales->contains($cuveCereale)) {
            $this->_cuveCereales[] = $cuveCereale;
            $cuveCereale->setOf($this);
        }

        return $this;
    }

    public function removeCuveCereale(CuveCereales $cuveCereale): self
    {
        if ($this->_cuveCereales->removeElement($cuveCereale)) {
            // set the owning side to null (unless already changed)
            if ($cuveCereale->getOf() === $this) {
                $cuveCereale->setOf(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('OF #%d - %s', $this->numero ?? $this->id, $this->produit ?? 'Sans produit');
    }
}
