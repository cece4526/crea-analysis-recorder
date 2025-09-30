<?php

namespace App\Entity;

use App\Repository\AnalyseSojaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class AnalyseSoja
 *
 * @category Entity
 * @package  App\Entity
 * @author   VotreNom <votre@email.com>
 * @license  MIT
 * @link     https://votreprojet.com
 *
 * @ORM\Entity(repositoryClass=AnalyseSojaRepository::class)
 */
class AnalyseSoja
{
    /**
     * Identifiant unique
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $_id = null;

    /**
     * Litrage décantation
     *
     * @ORM\Column(type="integer")
     */
    private ?int $_litrage_decan = null;

    /**
     * Température broyage
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_temperature_broyage = null;

    /**
     * Eau
     *
     * @ORM\Column(type="integer")
     */
    private ?int $_eau = null;

    /**
     * Matière
     *
     * @ORM\Column(type="integer")
     */
    private ?int $_matiere = null;

    /**
     * ES avant décantation
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_es_av_decan = null;

    /**
     * ES après décantation
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_es_ap_decan = null;

    /**
     * Contrôle visuel
     *
     * @ORM\Column(type="boolean")
     */
    private ?bool $_control_visuel = null;

    /**
     * Débit bicarbonate
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_debit_bicar = null;

    /**
     * Vitesse différencielle
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_vitesse_diff = null;

    /**
     * Couple
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_couple = null;

    /**
     * Variponds
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_variponds = null;

    /**
     * Contre pression
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_contre_pression = null;

    /**
     * Initial pilote
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $_initial_pilote = null;

    // Getters et setters

    public function getId(): ?int
    {
        return $this->_id;
    }

    public function getLitrageDecan(): ?int
    {
        return $this->_litrage_decan;
    }
    public function setLitrageDecan(?int $litrage_decan): self
    {
        $this->_litrage_decan = $litrage_decan;
        return $this;
    }

    public function getTemperatureBroyage(): ?string
    {
        return $this->_temperature_broyage;
    }
    public function setTemperatureBroyage(?string $temperature_broyage): self
    {
        $this->_temperature_broyage = $temperature_broyage;
        return $this;
    }

    public function getEau(): ?int
    {
        return $this->_eau;
    }
    public function setEau(?int $eau): self
    {
        $this->_eau = $eau;
        return $this;
    }

    public function getMatiere(): ?int
    {
        return $this->_matiere;
    }
    public function setMatiere(?int $matiere): self
    {
        $this->_matiere = $matiere;
        return $this;
    }

    public function getEsAvDecan(): ?string
    {
        return $this->_es_av_decan;
    }
    public function setEsAvDecan(?string $es_av_decan): self
    {
        $this->_es_av_decan = $es_av_decan;
        return $this;
    }

    public function getEsApDecan(): ?string
    {
        return $this->_es_ap_decan;
    }
    public function setEsApDecan(?string $es_ap_decan): self
    {
        $this->_es_ap_decan = $es_ap_decan;
        return $this;
    }

    public function isControlVisuel(): ?bool
    {
        return $this->_control_visuel;
    }
    public function setControlVisuel(?bool $control_visuel): self
    {
        $this->_control_visuel = $control_visuel;
        return $this;
    }

    public function getDebitBicar(): ?string
    {
        return $this->_debit_bicar;
    }
    public function setDebitBicar(?string $debit_bicar): self
    {
        $this->_debit_bicar = $debit_bicar;
        return $this;
    }

    public function getVitesseDiff(): ?string
    {
        return $this->_vitesse_diff;
    }
    public function setVitesseDiff(?string $vitesse_diff): self
    {
        $this->_vitesse_diff = $vitesse_diff;
        return $this;
    }

    public function getCouple(): ?string
    {
        return $this->_couple;
    }
    public function setCouple(?string $couple): self
    {
        $this->_couple = $couple;
        return $this;
    }

    public function getVariponds(): ?string
    {
        return $this->_variponds;
    }
    public function setVariponds(?string $variponds): self
    {
        $this->_variponds = $variponds;
        return $this;
    }

    public function getContrePression(): ?string
    {
        return $this->_contre_pression;
    }
    public function setContrePression(?string $contre_pression): self
    {
        $this->_contre_pression = $contre_pression;
        return $this;
    }

    public function getInitialPilote(): ?string
    {
        return $this->_initial_pilote;
    }
    public function setInitialPilote(?string $initial_pilote): self
    {
        $this->_initial_pilote = $initial_pilote;
        return $this;
    }
}
