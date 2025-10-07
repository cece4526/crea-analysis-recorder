<?php

namespace App\Entity;

use App\Repository\ProductionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: ProductionRepository::class)]
#[ORM\Table(name: 'production')]
class Production
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $quantity = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\OneToMany(targetEntity: OF::class, mappedBy: 'production')]
    private Collection $ofs;

    public function __construct()
    {
        $this->ofs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getOfs(): Collection
    {
        return $this->ofs;
    }

    public function addOf(OF $of): self
    {
        if (!$this->ofs->contains($of)) {
            $this->ofs[] = $of;
            $of->setProduction($this);
        }
        return $this;
    }

    public function removeOf(OF $of): self
    {
        if ($this->ofs->removeElement($of)) {
            if ($of->getProduction() === $this) {
                $of->setProduction(null);
            }
        }
        return $this;
    }
}
