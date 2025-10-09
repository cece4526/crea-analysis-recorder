<?php

namespace App\Entity;

use App\Repository\HACCPRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

/**
 * Entité HACCP
 */
#[ORM\Entity(repositoryClass: HACCPRepository::class)]
#[ORM\Table(name: 'haccp')]
class HACCP
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $filtre_pasteurisateur_resultat = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $temperature_cible = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $temperature_indique = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $filtre_nep_resultat = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $of_id = null;

    #[ORM\Column(name: 'initialProduction', type: Types::STRING, length: 255, nullable: true)]
    private ?string $initialProduction = null;

    #[ORM\Column(name: 'initialNEP', type: Types::STRING, length: 255, nullable: true)]
    private ?string $initialNEP = null;

    #[ORM\Column(name: 'initialTEMP', type: Types::STRING, length: 255, nullable: true)]
    private ?string $initialTEMP = null;

    /**
     * Retourne l'identifiant du contrôle HACCP.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le résultat du filtre pasteurisateur.
     *
     * @return bool|null
     */
    public function getFiltrePasteurisateurResultat(): ?bool
    {
        return $this->filtre_pasteurisateur_resultat;
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
        $this->filtre_pasteurisateur_resultat = $val;
        return $this;
    }

    /**
     * Retourne la température cible.
     *
     * @return int|null
     */
    public function getTemperatureCible(): ?int
    {
        return $this->temperature_cible;
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
        $this->temperature_cible = $val;
        return $this;
    }

    /**
     * Retourne la température indiquée.
     *
     * @return int|null
     */
    public function getTemperatureIndique(): ?int
    {
        return $this->temperature_indique;
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
        $this->temperature_indique = $val;
        return $this;
    }

    /**
     * Retourne le résultat du filtre NEP.
     *
     * @return bool|null
     */
    public function getFiltreNepResultat(): ?bool
    {
        return $this->filtre_nep_resultat;
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
        $this->filtre_nep_resultat = $val;
        return $this;
    }

    /**
     * Retourne l'ID de l'OF.
     *
     * @return int|null
     */
    public function getOfId(): ?int
    {
        return $this->of_id;
    }

    /**
     * Définit l'ID de l'OF.
     *
     * @param int|null $val ID de l'OF
     *
     * @return self
     */
    public function setOfId(?int $val): self
    {
        $this->of_id = $val;
        return $this;
    }

    /**
     * Retourne l'initial de production.
     *
     * @return string|null
     */
    public function getInitialProduction(): ?string
    {
        return $this->initialProduction;
    }

    /**
     * Définit l'initial de production.
     *
     * @param string|null $val Initial de production
     *
     * @return self
     */
    public function setInitialProduction(?string $val): self
    {
        $this->initialProduction = $val;
        return $this;
    }

    /**
     * Retourne l'initial NEP.
     *
     * @return string|null
     */
    public function getInitialNEP(): ?string
    {
        return $this->initialNEP;
    }

    /**
     * Définit l'initial NEP.
     *
     * @param string|null $val Initial NEP
     *
     * @return self
     */
    public function setInitialNEP(?string $val): self
    {
        $this->initialNEP = $val;
        return $this;
    }

    /**
     * Retourne l'initial TEMP.
     *
     * @return string|null
     */
    public function getInitialTEMP(): ?string
    {
        return $this->initialTEMP;
    }

    /**
     * Définit l'initial TEMP.
     *
     * @param string|null $val Initial TEMP
     *
     * @return self
     */
    public function setInitialTEMP(?string $val): self
    {
        $this->initialTEMP = $val;
        return $this;
    }
}
