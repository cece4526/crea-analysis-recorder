<?php

namespace App\Entity;

use App\Repository\HeureEnzymeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

/**
 * Entité HeureEnzyme - Hydrolyse enzymatique dans le workflow CÉRÉALES
 * Étape cruciale entre les cuves et les décanteurs
 */
#[ORM\Entity(repositoryClass: HeureEnzymeRepository::class)]
#[ORM\Table(name: 'heure_enzyme')]
class HeureEnzyme
{
    #[ORM\ManyToOne(targetEntity: OF::class, inversedBy: '_heureEnzymes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OF $of = null;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(name: 'heure_debut', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $heureDebut = null;

    #[ORM\Column(name: 'heure_fin', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $heureFin = null;

    #[ORM\Column(name: 'type_enzyme', type: Types::STRING, length: 100)]
    private ?string $typeEnzyme = null;

    #[ORM\Column(name: 'quantite_enzyme', type: Types::DECIMAL, precision: 8, scale: 3, nullable: true)]
    private ?string $quantiteEnzyme = null;

    #[ORM\Column(name: 'temperature_hydrolyse', type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $temperatureHydrolyse = null;

    #[ORM\Column(name: 'ph_initial', type: Types::DECIMAL, precision: 4, scale: 2, nullable: true)]
    private ?string $phInitial = null;

    #[ORM\Column(name: 'ph_final', type: Types::DECIMAL, precision: 4, scale: 2, nullable: true)]
    private ?string $phFinal = null;

    #[ORM\Column(name: 'duree_hydrolyse', type: Types::INTEGER, nullable: true)]
    private ?int $dureeHydrolyse = null;

    #[ORM\Column(name: 'efficacite_hydrolyse', type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $efficaciteHydrolyse = null;

    #[ORM\Column(name: 'conformite', type: Types::BOOLEAN)]
    private ?bool $conformite = null;

    #[ORM\Column(name: 'observations', type: Types::TEXT, nullable: true)]
    private ?string $observations = null;

    #[ORM\Column(name: 'operateur', type: Types::STRING, length: 100)]
    private ?string $operateur = null;

    /**
     * Retourne l'identifiant de l'heure enzyme.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne l'heure de début d'hydrolyse.
     */
    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heureDebut;
    }

    /**
     * Définit l'heure de début d'hydrolyse.
     */
    public function setHeureDebut(?\DateTimeInterface $heureDebut): self
    {
        $this->heureDebut = $heureDebut;
        return $this;
    }

    /**
     * Retourne l'heure de fin d'hydrolyse.
     */
    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heureFin;
    }

    /**
     * Définit l'heure de fin d'hydrolyse.
     */
    public function setHeureFin(?\DateTimeInterface $heureFin): self
    {
        $this->heureFin = $heureFin;
        return $this;
    }

    /**
     * Retourne le type d'enzyme utilisé.
     */
    public function getTypeEnzyme(): ?string
    {
        return $this->typeEnzyme;
    }

    /**
     * Définit le type d'enzyme utilisé.
     */
    public function setTypeEnzyme(?string $typeEnzyme): self
    {
        $this->typeEnzyme = $typeEnzyme;
        return $this;
    }

    /**
     * Retourne la quantité d'enzyme convertie en float.
     */
    public function getQuantiteEnzyme(): ?float
    {
        return $this->quantiteEnzyme ? (float) $this->quantiteEnzyme : null;
    }

    /**
     * Définit la quantité d'enzyme.
     */
    public function setQuantiteEnzyme(?float $quantiteEnzyme): self
    {
        $this->quantiteEnzyme = $quantiteEnzyme ? (string) $quantiteEnzyme : null;
        return $this;
    }

    /**
     * Retourne la température d'hydrolyse en °C convertie en float.
     */
    public function getTemperatureHydrolyse(): ?float
    {
        return $this->temperatureHydrolyse ? (float) $this->temperatureHydrolyse : null;
    }

    /**
     * Définit la température d'hydrolyse en °C.
     */
    public function setTemperatureHydrolyse(?float $temperatureHydrolyse): self
    {
        $this->temperatureHydrolyse = $temperatureHydrolyse ? (string) $temperatureHydrolyse : null;
        return $this;
    }

    /**
     * Retourne le pH initial converti en float.
     */
    public function getPhInitial(): ?float
    {
        return $this->phInitial ? (float) $this->phInitial : null;
    }

    /**
     * Définit le pH initial.
     */
    public function setPhInitial(?float $phInitial): self
    {
        $this->phInitial = $phInitial ? (string) $phInitial : null;
        return $this;
    }

    /**
     * Retourne le pH final converti en float.
     */
    public function getPhFinal(): ?float
    {
        return $this->phFinal ? (float) $this->phFinal : null;
    }

    /**
     * Définit le pH final.
     */
    public function setPhFinal(?float $phFinal): self
    {
        $this->phFinal = $phFinal ? (string) $phFinal : null;
        return $this;
    }

    /**
     * Retourne la durée d'hydrolyse en minutes.
     */
    public function getDureeHydrolyse(): ?int
    {
        return $this->dureeHydrolyse;
    }

    /**
     * Définit la durée d'hydrolyse en minutes.
     */
    public function setDureeHydrolyse(?int $dureeHydrolyse): self
    {
        $this->dureeHydrolyse = $dureeHydrolyse;
        return $this;
    }

    /**
     * Retourne l'efficacité d'hydrolyse en pourcentage convertie en float.
     */
    public function getEfficaciteHydrolyse(): ?float
    {
        return $this->efficaciteHydrolyse ? (float) $this->efficaciteHydrolyse : null;
    }

    /**
     * Définit l'efficacité d'hydrolyse en pourcentage.
     */
    public function setEfficaciteHydrolyse(?float $efficaciteHydrolyse): self
    {
        $this->efficaciteHydrolyse = $efficaciteHydrolyse ? (string) $efficaciteHydrolyse : null;
        return $this;
    }

    /**
     * Retourne la conformité de l'hydrolyse.
     */
    public function getConformite(): ?bool
    {
        return $this->conformite;
    }

    /**
     * Définit la conformité de l'hydrolyse.
     */
    public function setConformite(?bool $conformite): self
    {
        $this->conformite = $conformite;
        return $this;
    }

    /**
     * Retourne les observations.
     */
    public function getObservations(): ?string
    {
        return $this->observations;
    }

    /**
     * Définit les observations.
     */
    public function setObservations(?string $observations): self
    {
        $this->observations = $observations;
        return $this;
    }

    /**
     * Retourne l'opérateur responsable.
     */
    public function getOperateur(): ?string
    {
        return $this->operateur;
    }

    /**
     * Définit l'opérateur responsable.
     */
    public function setOperateur(?string $operateur): self
    {
        $this->operateur = $operateur;
        return $this;
    }

    /**
     * Retourne l'OF associé.
     */
    public function getOf(): ?OF
    {
        return $this->of;
    }

    /**
     * Définit l'OF associé.
     */
    public function setOf(?OF $of): self
    {
        $this->of = $of;
        return $this;
    }

    /**
     * Calcule la durée effective d'hydrolyse.
     */
    public function getDureeEffective(): ?int
    {
        if (!$this->heureDebut || !$this->heureFin) {
            return null;
        }
        
        $diff = $this->heureDebut->diff($this->heureFin);
        return ($diff->h * 60) + $diff->i;
    }

    /**
     * Vérifie si l'hydrolyse est terminée.
     */
    public function isTerminee(): bool
    {
        return $this->heureFin !== null && $this->phFinal !== null;
    }
}
