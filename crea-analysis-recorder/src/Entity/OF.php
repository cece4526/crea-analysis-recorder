
<?php
use App\Entity\CuveCereales;
use App\Entity\HeureEnzyme;
use App\Entity\DecanteurCereales;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

namespace App\Entity;

use App\Repository\OFRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\AnalyseSoja;

/**
 * Entité OF
 *
 * @ORM\Entity(repositoryClass=OFRepository::class)
 */
class OF

    /**
     * @ORM\OneToMany(targetEntity=CuveCereales::class, mappedBy="of", cascade={"persist", "remove"})
     */
    private Collection $cuveCereales;

    /**
     * @ORM\OneToMany(targetEntity=HeureEnzyme::class, mappedBy="of", cascade={"persist", "remove"})
     */
    private Collection $heureEnzymes;

    /**
     * @ORM\OneToMany(targetEntity=DecanteurCereales::class, mappedBy="of", cascade={"persist", "remove"})
     */
    private Collection $decanteurCereales;

    /**
     * @ORM\OneToMany(targetEntity=AnalyseSoja::class, mappedBy="of", cascade={"persist", "remove"})
     */
    private Collection $analyseSojas;

    public function __construct()
    {
        $this->cuveCereales = new ArrayCollection();
        $this->heureEnzymes = new ArrayCollection();
        $this->decanteurCereales = new ArrayCollection();
        $this->analyseSojas = new ArrayCollection();
    }

    /**
     * @return Collection<int, CuveCereales>
     */
    public function getCuveCereales(): Collection
    {
        return $this->cuveCereales;
    }

    public function addCuveCereale(CuveCereales $cuveCereale): self
    {
        if (!$this->cuveCereales->contains($cuveCereale)) {
            $this->cuveCereales[] = $cuveCereale;
            $cuveCereale->setOf($this);
        }
        return $this;
    }

    public function removeCuveCereale(CuveCereales $cuveCereale): self
    {
        if ($this->cuveCereales->removeElement($cuveCereale)) {
            if ($cuveCereale->getOf() === $this) {
                $cuveCereale->setOf(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, HeureEnzyme>
     */
    public function getHeureEnzymes(): Collection
    {
        return $this->heureEnzymes;
    }

    public function addHeureEnzyme(HeureEnzyme $heureEnzyme): self
    {
        if (!$this->heureEnzymes->contains($heureEnzyme)) {
            $this->heureEnzymes[] = $heureEnzyme;
            $heureEnzyme->setOf($this);
        }
        return $this;
    }

    public function removeHeureEnzyme(HeureEnzyme $heureEnzyme): self
    {
        if ($this->heureEnzymes->removeElement($heureEnzyme)) {
            if ($heureEnzyme->getOf() === $this) {
                $heureEnzyme->setOf(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, DecanteurCereales>
     */
    public function getDecanteurCereales(): Collection
    {
        return $this->decanteurCereales;
    }

    public function addDecanteurCereale(DecanteurCereales $decanteurCereale): self
    {
        if (!$this->decanteurCereales->contains($decanteurCereale)) {
            $this->decanteurCereales[] = $decanteurCereale;
            $decanteurCereale->setOf($this);
        }
        return $this;
    }

    public function removeDecanteurCereale(DecanteurCereales $decanteurCereale): self
    {
        if ($this->decanteurCereales->removeElement($decanteurCereale)) {
            if ($decanteurCereale->getOf() === $this) {
                $decanteurCereale->setOf(null);
            }
        }
        return $this;
    }
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
     * @ORM\Column(type="integer")
     */
    private ?int $_numero = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $_nature = null;


    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $_date = null;

    /**
     * @ORM\ManyToOne(targetEntity=Production::class, inversedBy="ofs")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Production $production = null;


    /**
     * @ORM\OneToMany(targetEntity=AnalyseSoja::class, mappedBy="of", cascade={"persist", "remove"})
     */
    private Collection $analyseSojas;

    public function __construct()
    {
        $this->analyseSojas = new ArrayCollection();
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
    public function getNumero(): ?int
    {
        return $this->_numero;
    }
    public function setNumero(int $numero): self
    {
        $this->_numero = $numero;
        return $this;
    }
    public function getNature(): ?string
    {
        return $this->_nature;
    }
    public function setNature(string $nature): self
    {
        $this->_nature = $nature;
        return $this;
    }
    public function getDate(): ?\DateTimeInterface
    {
        return $this->_date;
    }
    public function setDate(\DateTimeInterface $date): self
    {
        $this->_date = $date;
        return $this;
    }

    /**
     * @ORM\OneToOne(targetEntity=Okara::class, inversedBy="of", cascade={"persist", "remove"})
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

    /**
     * @ORM\OneToOne(targetEntity=HACCP::class, mappedBy="of", cascade={"persist", "remove"})
     */
    private ?HACCP $haccp = null;

    public function getHaccp(): ?HACCP
    {
        return $this->haccp;
    }

    public function setHaccp(?HACCP $haccp): self
    {
        $this->haccp = $haccp;
        return $this;
    }


    /**
     * @return Collection<int, AnalyseSoja>
     */
    public function getAnalyseSojas(): Collection
    {
        return $this->analyseSojas;
    }

    public function addAnalyseSoja(AnalyseSoja $analyseSoja): self
    {
        if (!$this->analyseSojas->contains($analyseSoja)) {
            $this->analyseSojas[] = $analyseSoja;
            $analyseSoja->setOf($this);
        }
        return $this;
    }

    public function removeAnalyseSoja(AnalyseSoja $analyseSoja): self
    {
        if ($this->analyseSojas->removeElement($analyseSoja)) {
            if ($analyseSoja->getOf() === $this) {
                $analyseSoja->setOf(null);
            }
        }
        return $this;
    }

    public function getProduction(): ?Production
    {
        return $this->production;
    }

    public function setProduction(?Production $production): self
    {
        $this->production = $production;
        return $this;
    }
}
