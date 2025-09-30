use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
<?php


namespace App\Entity;

use App\Repository\ProductionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité Production
 *
 * @ORM\Entity(repositoryClass=ProductionRepository::class)
 */
class Production
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $_id = null;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private ?string $_name = null;

    /**
     * @ORM\OneToMany(targetEntity=OF::class, mappedBy="production", orphanRemoval=true)
     */
    /**
     * @var Collection<int, OF>
     */
    private Collection $ofs;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"default":0})
     */
    private int $_quantity = 0;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $_status = null;

    /**
     * Get id
     * @return int|null
     */
    public function __construct()
    {
        $this->ofs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->_id;
    }
    /**
     * @return Collection<int, OF>
     */
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
            // set the owning side to null (unless already changed)
            if ($of->getProduction() === $this) {
                $of->setProduction(null);
            }
        }
        return $this;
    }

    /**
     * Get name
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->_name;
    }

    /**
     * Set name
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * Get quantity
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->_quantity;
    }

    /**
     * Set quantity
     * @param int $quantity
     * @return self
     */
    public function setQuantity(int $quantity): self
    {
        $this->_quantity = $quantity;
        return $this;
    }

    /**
     * Get status
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->_status;
    }

    /**
     * Set status
     * @param string|null $status
     * @return self
     */
    public function setStatus(?string $status): self
    {
        $this->_status = $status;
        return $this;
    }
}
