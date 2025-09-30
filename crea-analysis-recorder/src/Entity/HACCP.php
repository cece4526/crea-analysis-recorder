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

    public function getId(): ?int
    {
        return $this->_id;
    }
    public function getFiltrePasteurisateurResultat(): ?bool
    {
        return $this->_filtre_pasteurisateur_resultat;
    }
    public function setFiltrePasteurisateurResultat(?bool $val): self
    {
        $this->_filtre_pasteurisateur_resultat = $val;
        return $this;
    }
    public function getTemperatureCible(): ?int
    {
        return $this->_temperature_cible;
    }
    public function setTemperatureCible(?int $val): self
    {
        $this->_temperature_cible = $val;
        return $this;
    }
    public function getTemperatureIndique(): ?int
    {
        return $this->_temperature_indique;
    }
    public function setTemperatureIndique(?int $val): self
    {
        $this->_temperature_indique = $val;
        return $this;
    }
    public function getFiltreNepResultat(): ?bool
    {
        return $this->_filtre_nep_resultat;
    }
    public function setFiltreNepResultat(?bool $val): self
    {
        $this->_filtre_nep_resultat = $val;
        return $this;
    }

    /**
     * @ORM\OneToOne(targetEntity=OF::class, inversedBy="haccp", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?OF $of = null;

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
