<?php

namespace App\Entity;

use App\Repository\HACCPRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité HACCP
 *
 * @ORM\Entity(repositoryClass=HACCPRepository::class)
 */
class HACCP
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $_id = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $_filtre_pasteurisateur_resultat = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $_temperature_cible = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $_temperature_indique = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $_filtre_nep_resultat = null;

    /**
     * Retourne l'identifiant du contrôle HACCP.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->_id;
    }

    /**
     * Retourne le résultat du filtre pasteurisateur.
     *
     * @return bool|null
     */
    public function getFiltrePasteurisateurResultat(): ?bool
    {
        return $this->_filtre_pasteurisateur_resultat;
    }

    /**
     * Définit le résultat du filtre pasteurisateur.
     *
     * @param bool|null $val Résultat du filtre pasteurisateur
     *
     * @return self
     */
    public function setFiltrePasteurisateurResultat(?bool $val): self
    {
        $this->_filtre_pasteurisateur_resultat = $val;
        return $this;
    }

    /**
     * Retourne la température cible.
     *
     * @return int|null
     */
    public function getTemperatureCible(): ?int
    {
        return $this->_temperature_cible;
    }

    /**
     * Définit la température cible.
     *
     * @param int|null $val Température cible
     *
     * @return self
     */
    public function setTemperatureCible(?int $val): self
    {
        $this->_temperature_cible = $val;
        return $this;
    }

    /**
     * Retourne la température indiquée.
     *
     * @return int|null
     */
    public function getTemperatureIndique(): ?int
    {
        return $this->_temperature_indique;
    }

    /**
     * Définit la température indiquée.
     *
     * @param int|null $val Température indiquée
     *
     * @return self
     */
    public function setTemperatureIndique(?int $val): self
    {
        $this->_temperature_indique = $val;
        return $this;
    }

    /**
     * Retourne le résultat du filtre NEP.
     *
     * @return bool|null
     */
    public function getFiltreNepResultat(): ?bool
    {
        return $this->_filtre_nep_resultat;
    }

    /**
     * Définit le résultat du filtre NEP.
     *
     * @param bool|null $val Résultat du filtre NEP
     *
     * @return self
     */
    public function setFiltreNepResultat(?bool $val): self
    {
        $this->_filtre_nep_resultat = $val;
        return $this;
    }

    /**
     * @ORM\OneToOne(targetEntity=OF::class, inversedBy="haccp", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?OF $_of = null;

    /**
     * Retourne l'OF associé à ce contrôle HACCP.
     *
     * @return OF|null
     */
    public function getOf(): ?OF
    {
        return $this->_of;
    }

    /**
     * Définit l'OF associé à ce contrôle HACCP.
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
}
