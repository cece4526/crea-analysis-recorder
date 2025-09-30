<?php

namespace App\Entity;

use App\Repository\OFRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\QuantiteEnzyme;
use App\Entity\CuveCereales;
use App\Entity\HeureEnzyme;
use App\Entity\DecanteurCereales;
use App\Entity\AnalyseSoja;

/**
 * Entité OF
 *
 * @ORM\Entity(repositoryClass=OFRepository::class)
 */
class OF
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
    private ?Production $_production = null;

    /**
     * @ORM\OneToMany(targetEntity=QuantiteEnzyme::class, mappedBy="of", cascade={"persist", "remove"})
     */
    private Collection $_quantiteEnzymes;

    /**
     * @ORM\OneToMany(targetEntity=CuveCereales::class, mappedBy="of", cascade={"persist", "remove"})
     */
    private Collection $_cuveCereales;

    /**
     * @ORM\OneToMany(targetEntity=HeureEnzyme::class, mappedBy="of", cascade={"persist", "remove"})
     */
    private Collection $_heureEnzymes;

    /**
     * @ORM\OneToMany(targetEntity=DecanteurCereales::class, mappedBy="of", cascade={"persist", "remove"})
     */
    private Collection $_decanteurCereales;

    /**
     * @ORM\OneToMany(targetEntity=AnalyseSoja::class, mappedBy="of", cascade={"persist", "remove"})
     */
    private Collection $_analyseSojas;

    /**
     * @ORM\OneToOne(targetEntity=Okara::class, inversedBy="of", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Okara $_okara = null;

    /**
     * @ORM\OneToOne(targetEntity=HACCP::class, mappedBy="of", cascade={"persist", "remove"})
     */
    private ?HACCP $_haccp = null;


    /**
     * @ORM\OneToOne(targetEntity=AvCorrectSoja::class, mappedBy="_of", cascade={"persist", "remove"})
     */
    private ?AvCorrectSoja $_avCorrectSoja = null;

    /**
     * @ORM\OneToOne(targetEntity=AvCorrectCereales::class, mappedBy="_of", cascade={"persist", "remove"})
     */
    private ?AvCorrectCereales $_avCorrectCereales = null;

    public function __construct()
    {
        $this->_quantiteEnzymes = new ArrayCollection();
        $this->_cuveCereales = new ArrayCollection();
        $this->_heureEnzymes = new ArrayCollection();
        $this->_decanteurCereales = new ArrayCollection();
        $this->_analyseSojas = new ArrayCollection();
    }

    // ... Générer les getters et setters pour toutes les propriétés, y compris les relations ...
}
