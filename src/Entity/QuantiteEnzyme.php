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

    /**
     * Constructeur : initialise la collection d'enzymes.
     */
    public function __construct()
    {
        $this->_enzymes = new ArrayCollection();
    }

    /**
     * Retourne la collection des enzymes associées à cette quantité.
     *
     * @return Collection<int, Enzyme>
     */
    public function getEnzymes(): Collection
    {
        return $this->_enzymes;
    }

    /**
     * Ajoute une enzyme à la collection.
     *
     * @param Enzyme $enzyme L'enzyme à ajouter
     *
     * @return self
     */
    public function addEnzyme(Enzyme $enzyme): self
    {
        if (!$this->_enzymes->contains($enzyme)) {
            $this->_enzymes[] = $enzyme;
        }
        return $this;
    }

    /**
     * Retire une enzyme de la collection.
     *
     * @param Enzyme $enzyme L'enzyme à retirer
     *
     * @return self
     */
    public function removeEnzyme(Enzyme $enzyme): self
    {
        $this->_enzymes->removeElement($enzyme);
        return $this;
    }

    /**
     * Retourne l'OF associé à cette quantité d'enzyme.
     *
     * @return OF|null
     */
    public function getOf(): ?OF
    {
        return $this->_of;
    }

    /**
     * Définit l'OF associé à cette quantité d'enzyme.
     *
     * @param OF|null $of L'OF à associer
     *
     * @return self
     */
    public function setOf(?OF $of): self
    {
        $this->_of = $of;
        return $this;
    }

    /**
     * Retourne l'identifiant de la quantité d'enzyme.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->_id;
    }

    /**
     * Retourne le pourcentage d'enzyme.
     *
     * @return string|null
     */
    public function getPourcentage(): ?string
    {
        return $this->_pourcentage;
    }

    /**
     * Définit le pourcentage d'enzyme.
     *
     * @param string|null $pourcentage Le pourcentage d'enzyme
     *
     * @return self
     */
    public function setPourcentage(?string $pourcentage): self
    {
        $this->_pourcentage = $pourcentage;
        return $this;
    }

    /**
     * Retourne la quantité d'enzyme.
     *
     * @return string|null
     */
    public function getQuantite(): ?string
    {
        return $this->_quantite;
    }

    /**
     * Définit la quantité d'enzyme.
     *
     * @param string|null $quantite La quantité d'enzyme
     *
     * @return self
     */
    public function setQuantite(?string $quantite): self
    {
        $this->_quantite = $quantite;
        return $this;
    }
}
