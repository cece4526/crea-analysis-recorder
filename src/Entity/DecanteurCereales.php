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
     * @ORM\ManyToOne(targetEntity=OF::class, inversedBy="decanteurCereales")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?OF $of = null;
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

    /**
     * Retourne l'identifiant du décanteur céréales.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne l'OF associé.
     *
     * @return OF|null
     */
    public function getOf(): ?OF
    {
        return $this->of;
    }

    /**
     * Définit l'OF associé.
     *
     * @param OF|null $of L'OF à associer à ce décanteur céréales
     *
     * @return self
     */
    public function setOf(?OF $of): self
    {
        $this->of = $of;
        return $this;
    }

    /**
     * Retourne l'extrait sec avant décantation.
     *
     * @return string|null
     */
    public function getEsAvDecan(): ?string
    {
        return $this->es_av_decan;
    }

    /**
     * Définit l'extrait sec avant décantation.
     *
     * @param string|null $esAvDecan Valeur de l'extrait sec avant décantation
     *
     * @return self
     */
    public function setEsAvDecan(?string $esAvDecan): self
    {
        $this->es_av_decan = $esAvDecan;
        return $this;
    }

    /**
     * Retourne l'extrait sec après décantation.
     *
     * @return string|null
     */
    public function getEsApDecan(): ?string
    {
        return $this->es_ap_decan;
    }

    /**
     * Définit l'extrait sec après décantation.
     *
     * @param string|null $esApDecan Valeur de l'extrait sec après décantation
     *
     * @return self
     */
    public function setEsApDecan(?string $esApDecan): self
    {
        $this->es_ap_decan = $esApDecan;
        return $this;
    }

    /**
     * Retourne la vitesse différentielle.
     *
     * @return string|null
     */
    public function getVitesseDiff(): ?string
    {
        return $this->vitesse_diff;
    }

    /**
     * Définit la vitesse différentielle.
     *
     * @param string|null $vitesseDiff Valeur de la vitesse différentielle
     *
     * @return self
     */
    public function setVitesseDiff(?string $vitesseDiff): self
    {
        $this->vitesse_diff = $vitesseDiff;
        return $this;
    }

    /**
     * Retourne la valeur du variponds.
     *
     * @return string|null
     */
    public function getVariponds(): ?string
    {
        return $this->variponds;
    }

    /**
     * Définit la valeur du variponds.
     *
     * @param string|null $variponds Valeur du variponds
     *
     * @return self
     */
    public function setVariponds(?string $variponds): self
    {
        $this->variponds = $variponds;
        return $this;
    }

    /**
     * Retourne le couple.
     *
     * @return string|null
     */
    public function getCouple(): ?string
    {
        return $this->couple;
    }

    /**
     * Définit le couple.
     *
     * @param string|null $couple Valeur du couple
     *
     * @return self
     */
    public function setCouple(?string $couple): self
    {
        $this->couple = $couple;
        return $this;
    }

    /**
     * Retourne la contre-pression.
     *
     * @return string|null
     */
    public function getContrePression(): ?string
    {
        return $this->contre_pression;
    }

    /**
     * Définit la contre-pression.
     *
     * @param string|null $contrePression Valeur de la contre-pression
     *
     * @return self
     */
    public function setContrePression(?string $contrePression): self
    {
        $this->contre_pression = $contrePression;
        return $this;
    }
}
