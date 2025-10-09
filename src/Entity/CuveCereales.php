<?php

namespace App\Entity;

use App\Repository\CuveCerealesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

/**
 * Entité CuveCereales
 */
#[ORM\Entity(repositoryClass: CuveCerealesRepository::class)]
#[ORM\Table(name: 'cuve_cereales')]

/**
 * Entité CuveCereales
 *
 * @ORM\Entity(repositoryClass=CuveCerealesRepository::class)
 */
class CuveCereales
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: OF::class, inversedBy: '_cuveCereales')]
    #[ORM\JoinColumn(name: 'of_id', referencedColumnName: 'id', nullable: true)]
    private ?OF $of = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $cuve = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $debit_enzyme = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $temperature_hydrolise = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $matiere = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $quantite_enzyme = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $control_verre = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $initial_pilote = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $created_at = null;

    /**
     * Retourne l'identifiant de la cuve céréales.
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
     * @param OF|null $of L'OF à associer à cette cuve céréales
     *
     * @return self
     */
    public function setOf(?OF $of): self
    {
        $this->of = $of;
        return $this;
    }

    /**
     * Retourne le numéro de la cuve.
     *
     * @return int|null
     */
    public function getCuve(): ?int
    {
        return $this->cuve;
    }

    /**
     * Définit le numéro de la cuve.
     *
     * @param int|null $cuve Le numéro de la cuve
     *
     * @return self
     */
    public function setCuve(?int $cuve): self
    {
        $this->cuve = $cuve;
        return $this;
    }

    /**
     * Retourne le débit d'enzyme.
     *
     * @return string|null
     */
    public function getDebitEnzyme(): ?string
    {
        return $this->debit_enzyme;
    }

    /**
     * Définit le débit d'enzyme.
     *
     * @param string|null $debitEnzyme Le débit d'enzyme
     *
     * @return self
     */
    public function setDebitEnzyme(?string $debitEnzyme): self
    {
        $this->debit_enzyme = $debitEnzyme;
        return $this;
    }

    /**
     * Retourne la température d'hydrolyse.
     *
     * @return string|null
     */
    public function getTemperatureHydrolise(): ?string
    {
        return $this->temperature_hydrolise;
    }

    /**
     * Définit la température d'hydrolyse.
     *
     * @param string|null $temperatureHydrolise La température d'hydrolyse
     *
     * @return self
     */
    public function setTemperatureHydrolise(?string $temperatureHydrolise): self
    {
        $this->temperature_hydrolise = $temperatureHydrolise;
        return $this;
    }

    /**
     * Retourne la matière.
     *
     * @return string|null
     */
    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    /**
     * Définit la matière.
     *
     * @param string|null $matiere La matière
     *
     * @return self
     */
    public function setMatiere(?string $matiere): self
    {
        $this->matiere = $matiere;
        return $this;
    }

    /**
     * Retourne la quantité d'enzyme.
     *
     * @return string|null
     */
    public function getQuantiteEnzyme(): ?string
    {
        return $this->quantite_enzyme;
    }

    /**
     * Définit la quantité d'enzyme.
     *
     * @param string|null $quantiteEnzyme La quantité d'enzyme
     *
     * @return self
     */
    public function setQuantiteEnzyme(?string $quantiteEnzyme): self
    {
        $this->quantite_enzyme = $quantiteEnzyme;
        return $this;
    }

    /**
     * Retourne l'état du contrôle verre.
     *
     * @return bool|null
     */
    public function getControlVerre(): ?bool
    {
        return $this->control_verre;
    }

    /**
     * Définit l'état du contrôle verre.
     *
     * @param bool|null $controlVerre L'état du contrôle verre
     *
     * @return self
     */
    public function setControlVerre(?bool $controlVerre): self
    {
        $this->control_verre = $controlVerre;
        return $this;
    }

    /**
     * Retourne l'initial du pilote.
     *
     * @return string|null
     */
    public function getInitialPilote(): ?string
    {
        return $this->initial_pilote;
    }

    /**
     * Définit l'initial du pilote.
     *
     * @param string|null $initialPilote L'initial du pilote
     *
     * @return self
     */
    public function setInitialPilote(?string $initialPilote): self
    {
        $this->initial_pilote = $initialPilote;
        return $this;
    }

    /**
     * Retourne la date de création.
     *
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    /**
     * Définit la date de création.
     *
     * @param \DateTimeInterface|null $createdAt La date de création
     *
     * @return self
     */
    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->created_at = $createdAt;
        return $this;
    }
}
