
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

    // Getters et setters à générer ici

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
