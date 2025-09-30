<?php

namespace App\Entity;

use App\Repository\EchantillonsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité Echantillons
 *
 * @ORM\Entity(repositoryClass=EchantillonsRepository::class)
 */
class Echantillons
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $_id = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_poids_0 = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_poids_1 = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_poids_2 = null;

    public function getId(): ?int
    {
        return $this->_id;
    }
    public function getPoids0(): ?string
    {
        return $this->_poids_0;
    }
    public function setPoids0(?string $poids_0): self
    {
        $this->_poids_0 = $poids_0;
        return $this;
    }
    public function getPoids1(): ?string
    {
        return $this->_poids_1;
    }
    public function setPoids1(?string $poids_1): self
    {
        $this->_poids_1 = $poids_1;
        return $this;
    }
    public function getPoids2(): ?string
    {
        return $this->_poids_2;
    }
    public function setPoids2(?string $poids_2): self
    {
        $this->_poids_2 = $poids_2;
        return $this;
    }

    /**
     * @ORM\ManyToOne(targetEntity=Okara::class, inversedBy="echantillons")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Okara $okara = null;

    public function getOkara(): ?Okara
    {
        return $this->okara;
    }

    public function setOkara(?Okara $okara): self
    {
        $this->okara = $okara;
        return $this;
    }
}
