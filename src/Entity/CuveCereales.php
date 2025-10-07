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
    #[ORM\ManyToOne(targetEntity: OF::class, inversedBy: '_cuveCereales')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OF $of = null;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    private ?int $id = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $cuve = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $debit_enzyme = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $temperature_hydrolise = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $quantite_enzyme2 = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $matiere = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $control_verre = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $initial_pilote = null;

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
     * Retourne la quantité d'enzyme 2.
     *
     * @return string|null
     */
    public function getQuantiteEnzyme2(): ?string
    {
        return $this->quantite_enzyme2;
    }

    /**
     * Définit la quantité d'enzyme 2.
     *
     * @param string|null $quantiteEnzyme2 La quantité d'enzyme 2
     *
     * @return self
     */
    public function setQuantiteEnzyme2(?string $quantiteEnzyme2): self
    {
        $this->quantite_enzyme2 = $quantiteEnzyme2;
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
}
