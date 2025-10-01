
<?php

namespace App\Entity;


use App\Repository\HeureEnzymeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité HeureEnzyme
 *
 * @ORM\Entity(repositoryClass=HeureEnzymeRepository::class)
 */
class HeureEnzyme
{
    /**
     * @ORM\ManyToOne(targetEntity=OF::class, inversedBy="heureEnzymes")
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
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $heure = null;

    /**
     * Retourne l'identifiant de l'heure enzyme.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne l'heure associée.
     *
     * @return \DateTimeInterface|null
     */
    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    /**
     * Définit l'heure associée.
     *
     * @param \DateTimeInterface|null $heure Heure à associer
     *
     * @return self
     */
    public function setHeure(?\DateTimeInterface $heure): self
    {
        $this->heure = $heure;
        return $this;
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
     * @param OF|null $of L'OF à associer
     *
     * @return self
     */
    public function setOf(?OF $of): self
    {
        $this->of = $of;
        return $this;
    }
}
