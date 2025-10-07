<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Production
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $_id = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $_name = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $_quantity = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $_status = null;

    /**
     * @ORM\OneToMany(targetEntity=OF::class, mappedBy="production")
     */
    private Collection $_ofs;

    public function __construct()
    {
        $this->_ofs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->_id;
    }

    public function getName(): ?string
    {
        return $this->_name;
    }

    public function setName(?string $name): self
    {
        $this->_name = $name;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->_quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->_quantity = $quantity;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->_status;
    }

    public function setStatus(?string $status): self
    {
        $this->_status = $status;
        return $this;
    }

    /**
     * @return Collection<int, OF>
     */
    public function getOfs(): Collection
    {
        return $this->_ofs;
    }

    public function addOf(OF $of): self
    {
        if (!$this->_ofs->contains($of)) {
            $this->_ofs[] = $of;
            $of->setProduction($this);
        }
        return $this;
    }

    public function removeOf(OF $of): self
    {
        if ($this->_ofs->removeElement($of)) {
            if ($of->getProduction() === $this) {
                $of->setProduction(null);
            }
        }
        return $this;
    }
}
