<?php
namespace App\Entity;

use App\Repository\EnzymeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\QuantiteEnzyme;

/**
 * Entité Enzyme
 */
#[ORM\Entity(repositoryClass: EnzymeRepository::class)]
#[ORM\Table(name: 'enzyme')]
class Enzyme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    private ?int $_id = null;

    #[ORM\Column(name: 'name', type: Types::STRING, length: 255)]
    private ?string $_name = null;

    #[ORM\ManyToMany(targetEntity: QuantiteEnzyme::class, inversedBy: '_enzymes')]
    #[ORM\JoinTable(name: 'enzyme_quantite_enzyme')]
    #[ORM\JoinColumn(name: 'enzyme_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'quantite_enzyme_id', referencedColumnName: 'id')]
    private Collection $_quantiteEnzymes;

    /**
     * Constructeur : initialise la collection de quantités d'enzymes.
     */
    public function __construct()
    {
        $this->_quantiteEnzymes = new ArrayCollection();
    }

    /**
     * Retourne la collection des quantités d'enzymes associées à cette enzyme.
     *
     * @return Collection<int, QuantiteEnzyme>
     */
    public function getQuantiteEnzymes(): Collection
    {
        return $this->_quantiteEnzymes;
    }

    /**
     * Ajoute une quantité d'enzyme à la collection.
     *
     * @param QuantiteEnzyme $quantiteEnzyme La quantité d'enzyme à ajouter
     *
     * @return self
     */
    public function addQuantiteEnzyme(QuantiteEnzyme $quantiteEnzyme): self
    {
        if (!$this->_quantiteEnzymes->contains($quantiteEnzyme)) {
            $this->_quantiteEnzymes[] = $quantiteEnzyme;
        }
        return $this;
    }

    /**
     * Retire une quantité d'enzyme de la collection.
     *
     * @param QuantiteEnzyme $quantiteEnzyme La quantité d'enzyme à retirer
     *
     * @return self
     */
    public function removeQuantiteEnzyme(QuantiteEnzyme $quantiteEnzyme): self
    {
        $this->_quantiteEnzymes->removeElement($quantiteEnzyme);
        return $this;
    }

    /**
     * Retourne l'identifiant de l'enzyme.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->_id;
    }

    /**
     * Retourne le nom de l'enzyme.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->_name;
    }

    /**
     * Définit le nom de l'enzyme.
     *
     * @param string $name Le nom de l'enzyme
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->_name = $name;
        return $this;
    }
}
