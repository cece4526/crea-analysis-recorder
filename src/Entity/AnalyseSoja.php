<?php

namespace App\Entity;

use App\Entity\OF;
use App\Repository\AnalyseSojaRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

/**
 * Entité AnalyseSoja - Analyse qualité du soja
 */
#[ORM\Entity(repositoryClass: AnalyseSojaRepository::class)]
#[ORM\Table(name: 'analyse_soja')]
class AnalyseSoja
{
    /**
     * Identifiant unique
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    /**
     * Ordre de fabrication associé
     */
    #[ORM\ManyToOne(targetEntity: OF::class, inversedBy: '_analyseSojas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OF $of = null;

    /**
     * Litrage décantation
     */
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $litrageDecan = null;

    /**
     * Température broyage
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $temperatureBroyage = null;

    /**
     * Quantité d'eau
     */
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $eau = null;

    /**
     * Quantité de matière
     */
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $matiere = null;

    /**
     * ES avant décantation
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $esAvDecan = null;

    /**
     * ES après décantation
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $esApDecan = null;

    /**
     * Contrôle visuel effectué
     */
    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $controlVisuel = null;

    /**
     * Débit bicarbonate
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $debitBicar = null;

    /**
     * Vitesse différencielle
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $vitesseDiff = null;

    /**
     * Couple
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $couple = null;

    /**
     * Variponds
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $variponds = null;

    /**
     * Contre pression
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $contrePression = null;

    /**
     * Initiales du pilote
     */
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $initialPilote = null;

    /**
     * Date de l'analyse
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    // Méthodes pour OF
    public function getOf(): ?OF
    {
        return $this->of;
    }

    public function setOf(?OF $of): self
    {
        $this->of = $of;
        return $this;
    }

    // Méthodes ID
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne la date de l'analyse.
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Définit la date de l'analyse.
     */
    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Retourne le litrage décanté.
     */
    public function getLitrageDecan(): ?int
    {
        return $this->litrageDecan;
    }

    /**
     * Définit le litrage décanté.
     */
    public function setLitrageDecan(?int $litrageDecan): self
    {
        $this->litrageDecan = $litrageDecan;
        return $this;
    }

    /**
     * Retourne la température de broyage.
     */
    public function getTemperatureBroyage(): ?string
    {
        return $this->temperatureBroyage;
    }

    /**
     * Définit la température de broyage.
     */
    public function setTemperatureBroyage(?string $temperatureBroyage): self
    {
        $this->temperatureBroyage = $temperatureBroyage;
        return $this;
    }

    /**
     * Retourne la quantité d'eau.
     */
    public function getEau(): ?int
    {
        return $this->eau;
    }

    /**
     * Définit la quantité d'eau.
     */
    public function setEau(?int $eau): self
    {
        $this->eau = $eau;
        return $this;
    }

    /**
     * Retourne la quantité de matière.
     */
    public function getMatiere(): ?int
    {
        return $this->matiere;
    }

    /**
     * Définit la quantité de matière.
     */
    public function setMatiere(?int $matiere): self
    {
        $this->matiere = $matiere;
        return $this;
    }

    /**
     * Retourne l'ES avant décantation.
     */
    public function getEsAvDecan(): ?string
    {
        return $this->esAvDecan;
    }

    /**
     * Définit l'ES avant décantation.
     */
    public function setEsAvDecan(?string $esAvDecan): self
    {
        $this->esAvDecan = $esAvDecan;
        return $this;
    }

    /**
     * Retourne l'ES après décantation.
     */
    public function getEsApDecan(): ?string
    {
        return $this->esApDecan;
    }

    /**
     * Définit l'ES après décantation.
     */
    public function setEsApDecan(?string $esApDecan): self
    {
        $this->esApDecan = $esApDecan;
        return $this;
    }

    /**
     * Indique si le contrôle visuel a été effectué.
     */
    public function isControlVisuel(): ?bool
    {
        return $this->controlVisuel;
    }

    /**
     * Définit si le contrôle visuel a été effectué.
     */
    public function setControlVisuel(?bool $controlVisuel): self
    {
        $this->controlVisuel = $controlVisuel;
        return $this;
    }

    /**
     * Retourne le débit de bicarbonate.
     */
    public function getDebitBicar(): ?string
    {
        return $this->debitBicar;
    }

    /**
     * Définit le débit de bicarbonate.
     */
    public function setDebitBicar(?string $debitBicar): self
    {
        $this->debitBicar = $debitBicar;
        return $this;
    }

    /**
     * Retourne la vitesse de diffusion.
     */
    public function getVitesseDiff(): ?string
    {
        return $this->vitesseDiff;
    }

    /**
     * Définit la vitesse de diffusion.
     */
    public function setVitesseDiff(?string $vitesseDiff): self
    {
        $this->vitesseDiff = $vitesseDiff;
        return $this;
    }

    public function getCouple(): ?string
    {
        return $this->couple;
    }

    public function setCouple(?string $couple): self
    {
        $this->couple = $couple;
        return $this;
    }

    public function getVariponds(): ?string
    {
        return $this->variponds;
    }

    public function setVariponds(?string $variponds): self
    {
        $this->variponds = $variponds;
        return $this;
    }

    public function getContrePression(): ?string
    {
        return $this->contrePression;
    }

    public function setContrePression(?string $contrePression): self
    {
        $this->contrePression = $contrePression;
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
}
