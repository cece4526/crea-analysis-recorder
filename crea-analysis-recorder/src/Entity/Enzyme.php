
<?php


namespace App\Entity;

use App\Repository\EnzymeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\QuantiteEnzyme;

/**
 * Entité Enzyme
 *
 * @ORM\Entity(repositoryClass=EnzymeRepository::class)
 */
class Enzyme
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $_id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $_name = null;

    /**
     * @ORM\ManyToMany(targetEntity=QuantiteEnzyme::class, inversedBy="enzymes")
     */
    private Collection $_quantiteEnzymes;

    public function __construct()
    {
        $this->_quantiteEnzymes = new ArrayCollection();
    }

    /**
     * @return Collection<int, QuantiteEnzyme>
     */
    public function getQuantiteEnzymes(): Collection
    {
        return $this->_quantiteEnzymes;
    }

    public function addQuantiteEnzyme(QuantiteEnzyme $quantiteEnzyme): self
    {
        if (!$this->_quantiteEnzymes->contains($quantiteEnzyme)) {
            $this->_quantiteEnzymes[] = $quantiteEnzyme;
        }
        return $this;
    }

    public function removeQuantiteEnzyme(QuantiteEnzyme $quantiteEnzyme): self
    {
        $this->_quantiteEnzymes->removeElement($quantiteEnzyme);
        return $this;
    }

    public function getId(): ?int
    {
        return $this->_id;
    }

    public function getName(): ?string
    {
        return $this->_name;
    }

    public function setName(string $name): self
    {
        $this->_name = $name;
        return $this;
    }
}
