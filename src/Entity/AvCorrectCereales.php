<?php

namespace App\Entity;

use App\Repository\AvCorrectCerealesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

/**
 * Entité AvCorrectCereales
 */
#[ORM\Entity(repositoryClass: AvCorrectCerealesRepository::class)]
#[ORM\Table(name: 'av_correct_cereales')]
class AvCorrectCereales
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    private ?int $id = null;

    /**
     * #[ORM\Column(type: Types::DATETIME_MUTABLE)]
     */
    private ?\DateTimeInterface $date = null;

    /**
     * #[ORM\Column(type: Types::INTEGER)]
     */
    private ?int $tank = null;

    /**
     * #[ORM\Column(type: Types::INTEGER)]
     */
    private ?int $eau = null;

    /**
     * #[ORM\Column(type: Types::INTEGER)]
     */
    private ?int $matiere = null;

    /**
     * #[ORM\Column(type: Types::INTEGER)]
     */
    private ?int $produitFini = null;

    /**
     * #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
     */
    private ?string $esTank = null;

    /**
     * #[ORM\Column(type: Types::STRING, length: 255)]
     */
    private ?string $initialPilote = null;

    /**
     * OF associé à cette correction
     */
    #[ORM\OneToOne(targetEntity: OF::class, inversedBy: '_avCorrectCereales', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?OF $_of = null;

    /**
     * Retourne l'identifiant.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne la date.
     *
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Définit la date.
     *
     * @param \DateTimeInterface|null $date La date à définir
     *
     * @return self
     */
    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Retourne le tank.
     *
     * @return int|null
     */
    public function getTank(): ?int
    {
        return $this->tank;
    }

    /**
     * Définit le tank.
     *
     * @param int|null $tank Le numéro du tank
     *
     * @return self
     */
    public function setTank(?int $tank): self
    {
        $this->tank = $tank;
        return $this;
    }

    /**
     * Retourne l'eau.
     *
     * @return int|null
     */
    public function getEau(): ?int
    {
        return $this->eau;
    }

    /**
     * Définit l'eau.
     *
     * @param int|null $eau La quantité d'eau
     *
     * @return self
     */
    public function setEau(?int $eau): self
    {
        $this->eau = $eau;
        return $this;
    }

    /**
     * Retourne la matière.
     *
     * @return int|null
     */
    public function getMatiere(): ?int
    {
        return $this->matiere;
    }

    /**
     * Définit la matière.
     *
     * @param int|null $matiere La quantité de matière
     *
     * @return self
     */
    public function setMatiere(?int $matiere): self
    {
        $this->matiere = $matiere;
        return $this;
    }

    /**
     * Retourne le produit fini.
     *
     * @return int|null
     */
    public function getProduitFini(): ?int
    {
        return $this->produitFini;
    }

    /**
     * Définit le produit fini.
     *
     * @param int|null $produitFini La quantité de produit fini
     *
     * @return self
     */
    public function setProduitFini(?int $produitFini): self
    {
        $this->produitFini = $produitFini;
        return $this;
    }

    /**
     * Retourne l'extrait sec tank.
     *
     * @return string|null
     */
    public function getEsTank(): ?string
    {
        return $this->esTank;
    }

    /**
     * Définit l'extrait sec tank.
     *
     * @param string|null $esTank L'extrait sec du tank
     *
     * @return self
     */
    public function setEsTank(?string $esTank): self
    {
        $this->esTank = $esTank;
        return $this;
    }

    /**
     * Retourne l'initial pilote.
     *
     * @return string|null
     */
    public function getInitialPilote(): ?string
    {
        return $this->initialPilote;
    }

    /**
     * Définit l'initial pilote.
     *
     * @param string|null $initialPilote L'initial du pilote
     *
     * @return self
     */
    public function setInitialPilote(?string $initialPilote): self
    {
        $this->initialPilote = $initialPilote;
        return $this;
    }

    /**
     * Retourne l'OF associé.
     *
     * @return OF|null
     */
    public function getOf(): ?OF
    {
        return $this->_of;
    }

    /**
     * Définit l'OF associé.
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
