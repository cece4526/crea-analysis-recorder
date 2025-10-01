<?php

namespace App\Entity;

use App\Repository\OkaraRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
/**
 * Entité Okara
 *
 * @ORM\Entity(repositoryClass=OkaraRepository::class)
 */
class Okara
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $_id = null;

    /**
     * @ORM\OneToMany(targetEntity=Echantillons::class, mappedBy="okara", orphanRemoval=true)
     * @var Collection<int, Echantillons>
     */
    private Collection $echantillons;

    /**
     * @ORM\OneToOne(targetEntity=OF::class, mappedBy="okara", cascade={"persist", "remove"})
     */
    private ?OF $of = null;

    /**
     * Constructeur : initialise la collection d'échantillons.
     */
    public function __construct()
    {
        $this->echantillons = new ArrayCollection();
    }

    /**
     * Retourne l'OF associé à cet okara.
     *
     * @return OF|null
     */
    public function getOf(): ?OF
    {
        return $this->of;
    }

    /**
     * Définit l'OF associé à cet okara.
     *
     * @param OF|null $of L'OF à associer
     *
     * @return self
     */
    public function setOf(?OF $of): self
    {
        $this->of = $of;
        return $this;
    }

    /**
     * Retourne l'identifiant de l'okara.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->_id;
    }

    /**
     * Retourne la collection des échantillons liés à cet okara.
     *
     * @return Collection<int, Echantillons>
     */
    public function getEchantillons(): Collection
    {
        return $this->echantillons;
    }

    /**
     * Ajoute un échantillon à l'okara.
     *
     * @param Echantillons $echantillon L'échantillon à ajouter
     *
     * @return self
     */
    public function addEchantillon(Echantillons $echantillon): self
    {
        if (!$this->echantillons->contains($echantillon)) {
            $this->echantillons[] = $echantillon;
            $echantillon->setOkara($this);
        }
        return $this;
    }

    /**
     * Retire un échantillon de l'okara.
     *
     * @param Echantillons $echantillon L'échantillon à retirer
     *
     * @return self
     */
    public function removeEchantillon(Echantillons $echantillon): self
    {
        if ($this->echantillons->removeElement($echantillon)) {
            // set the owning side to null (unless already changed)
            if ($echantillon->getOkara() === $this) {
                $echantillon->setOkara(null);
            }
        }
        return $this;
    }
}
