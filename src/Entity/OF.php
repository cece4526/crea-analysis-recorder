<?php

namespace App\Entity;

use App\Repository\OFRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\QuantiteEnzyme;
use App\Entity\CuveCereales;
use App\Entity\HeureEnzyme;
use App\Entity\DecanteurCereales;
use App\Entity\AnalyseSoja;

/**
 * Entité OF
 */
#[ORM\Entity(repositoryClass: OFRepository::class)]
#[ORM\Table(name: '`of`')]
class OF
{
    /**
     * Identifiant unique
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    /**
     * Nom de l'OF
     */
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    /**
     * Numéro de l'OF
     */
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $numero = null;

    /**
     * Date de l'OF
     */
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    /**
     * Produit
     */
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $produit = null;

    /**
     * Quantité
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $quantite = null;

    /**
     * Statut de l'OF
     */
    #[ORM\Column(type: Types::STRING, length: 50)]
    private ?string $statut = 'en_attente';

    /**
     * Date de création
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * Date de mise à jour
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * Production associée à cet OF
     */
    #[ORM\ManyToOne(targetEntity: Production::class, inversedBy: 'ofs')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Production $production = null;

    // Relations temporairement commentées pour tests
    // /**
    //  * Collection des analyses soja
    //  */
    // #[ORM\OneToMany(targetEntity: AnalyseSoja::class, mappedBy: 'of', cascade: ['persist', 'remove'])]
    // private Collection $analyseSojas;

    /**
     * Okara associé à cet OF
     */
    #[ORM\OneToOne(targetEntity: Okara::class, mappedBy: 'of', cascade: ['persist', 'remove'])]
    private ?Okara $_okara = null;

    /**
     * HACCP associé à cet OF
     */
    #[ORM\OneToOne(targetEntity: HACCP::class, mappedBy: '_of', cascade: ['persist', 'remove'])]
    private ?HACCP $_haccp = null;

    /**
     * AvCorrectSoja associé à cet OF
     */
    #[ORM\OneToOne(targetEntity: AvCorrectSoja::class, mappedBy: '_of', cascade: ['persist', 'remove'])]
    private ?AvCorrectSoja $_avCorrectSoja = null;

    /**
     * AvCorrectCereales associé à cet OF
     */
    #[ORM\OneToOne(targetEntity: AvCorrectCereales::class, mappedBy: '_of', cascade: ['persist', 'remove'])]
    private ?AvCorrectCereales $_avCorrectCereales = null;

    /**
     * ApCorrectCereales associé à cet OF
     */
    #[ORM\OneToOne(targetEntity: ApCorrectCereales::class, mappedBy: '_of', cascade: ['persist', 'remove'])]
    private ?ApCorrectCereales $_apCorrectCereales = null;

    /**
     * ApCorrectSoja associé à cet OF
     */
    #[ORM\OneToOne(targetEntity: ApCorrectSoja::class, mappedBy: '_of', cascade: ['persist', 'remove'])]
    private ?ApCorrectSoja $_apCorrectSoja = null;

    /**
     * Collection des quantités d'enzymes
     */
    #[ORM\OneToMany(targetEntity: QuantiteEnzyme::class, mappedBy: '_of', cascade: ['persist', 'remove'])]
    private Collection $_quantiteEnzymes;

    /**
     * Collection des cuves céréales
     */
    #[ORM\OneToMany(targetEntity: CuveCereales::class, mappedBy: 'of', cascade: ['persist', 'remove'])]
    private Collection $_cuveCereales;

    /**
     * Collection des heures enzymes
     */
    #[ORM\OneToMany(targetEntity: HeureEnzyme::class, mappedBy: 'of', cascade: ['persist', 'remove'])]
    private Collection $_heureEnzymes;

    /**
     * Collection des décanteurs céréales
     */
    #[ORM\OneToMany(targetEntity: DecanteurCereales::class, mappedBy: 'of', cascade: ['persist', 'remove'])]
    private Collection $_decanteurCereales;

    /**
     * Collection des analyses soja
     */
    #[ORM\OneToMany(targetEntity: AnalyseSoja::class, mappedBy: 'of', cascade: ['persist', 'remove'])]
    private Collection $_analyseSojas;

    public function __construct()
    {
        $this->_quantiteEnzymes = new ArrayCollection();
        $this->_cuveCereales = new ArrayCollection();
        $this->_heureEnzymes = new ArrayCollection();
        $this->_decanteurCereales = new ArrayCollection();
        $this->_analyseSojas = new ArrayCollection();
        
        // Initialiser les dates automatiquement
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->statut = 'en_attente';
    }

        /**
         * Retourne l'identifiant de l'OF.
         */
        public function getId(): ?int
        {
            return $this->id;
        }

        /**
         * Retourne le nom de l'OF.
         */
        public function getName(): ?string
        {
            return $this->name;
        }

        /**
         * Définit le nom de l'OF.
         */
        public function setName(?string $name): self
        {
            $this->name = $name;
            return $this;
        }

        /**
         * Retourne le numéro de l'OF.
         */
        public function getNumero(): ?int
        {
            return $this->numero;
        }

        /**
         * Définit le numéro de l'OF.
         */
        public function setNumero(?int $numero): self
        {
            $this->numero = $numero;
            return $this;
        }

        /**
         * Retourne le produit de l'OF.
         */
        public function getProduit(): ?string
        {
            return $this->produit;
        }

        /**
         * Définit le produit de l'OF.
         */
        public function setProduit(?string $produit): self
        {
            $this->produit = $produit;
            return $this;
        }

        /**
         * Retourne la date de l'OF.
         */
        public function getDate(): ?\DateTimeInterface
        {
            return $this->date;
        }

        /**
         * Définit la date de l'OF.
         */
        public function setDate(?\DateTimeInterface $date): self
        {
            $this->date = $date;
            return $this;
        }

        /**
         * Retourne la quantité de l'OF.
         */
        public function getQuantite(): ?string
        {
            return $this->quantite;
        }

        /**
         * Définit la quantité de l'OF.
         */
        public function setQuantite(?string $quantite): self
        {
            $this->quantite = $quantite;
            return $this;
        }

        /**
         * Retourne le statut de l'OF.
         */
        public function getStatut(): ?string
        {
            return $this->statut;
        }

        /**
         * Définit le statut de l'OF.
         */
        public function setStatut(?string $statut): self
        {
            $this->statut = $statut;
            return $this;
        }

        /**
         * Retourne la date de création de l'OF.
         */
        public function getCreatedAt(): ?\DateTimeInterface
        {
            return $this->createdAt;
        }

        /**
         * Définit la date de création de l'OF.
         */
        public function setCreatedAt(?\DateTimeInterface $createdAt): self
        {
            $this->createdAt = $createdAt;
            return $this;
        }

        /**
         * Retourne la date de mise à jour de l'OF.
         */
        public function getUpdatedAt(): ?\DateTimeInterface
        {
            return $this->updatedAt;
        }

        /**
         * Définit la date de mise à jour de l'OF.
         */
        public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
        {
            $this->updatedAt = $updatedAt;
            return $this;
        }

        /**
         * Retourne la production associée à l'OF.
         */
        public function getProduction(): ?Production
        {
            return $this->production;
        }

        /**
         * Définit la production associée à l'OF.
         */
        public function setProduction(?Production $production): self
        {
            $this->production = $production;
            return $this;
        }

        /**
         * Retourne la collection des quantités d'enzymes associées à l'OF.
         *
         * @return Collection
         */
        public function getQuantiteEnzymes(): Collection
        {
            return $this->_quantiteEnzymes;
        }

        /**
         * Ajoute une quantité d'enzyme à l'OF.
         *
         * @param QuantiteEnzyme $quantiteEnzyme
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
         * Retire une quantité d'enzyme de l'OF.
         *
         * @param QuantiteEnzyme $quantiteEnzyme
         * @return self
         */
        public function removeQuantiteEnzyme(QuantiteEnzyme $quantiteEnzyme): self
        {
            $this->_quantiteEnzymes->removeElement($quantiteEnzyme);
            return $this;
        }

        /**
         * Retourne la collection des cuves céréales associées à l'OF.
         *
         * @return Collection
         */
        public function getCuveCereales(): Collection
        {
            return $this->_cuveCereales;
        }

        /**
         * Ajoute une cuve céréales à l'OF.
         *
         * @param CuveCereales $cuveCereale
         * @return self
         */
        public function addCuveCereale(CuveCereales $cuveCereale): self
        {
            if (!$this->_cuveCereales->contains($cuveCereale)) {
                $this->_cuveCereales[] = $cuveCereale;
            }
            return $this;
        }

        /**
         * Retire une cuve céréales de l'OF.
         *
         * @param CuveCereales $cuveCereale
         * @return self
         */
        public function removeCuveCereale(CuveCereales $cuveCereale): self
        {
            $this->_cuveCereales->removeElement($cuveCereale);
            return $this;
        }

        /**
         * Retourne la collection des heures enzymes associées à l'OF.
         *
         * @return Collection
         */
        public function getHeureEnzymes(): Collection
        {
            return $this->_heureEnzymes;
        }

        /**
         * Ajoute une heure enzyme à l'OF.
         *
         * @param HeureEnzyme $heureEnzyme
         * @return self
         */
        public function addHeureEnzyme(HeureEnzyme $heureEnzyme): self
        {
            if (!$this->_heureEnzymes->contains($heureEnzyme)) {
                $this->_heureEnzymes[] = $heureEnzyme;
            }
            return $this;
        }

        /**
         * Retire une heure enzyme de l'OF.
         *
         * @param HeureEnzyme $heureEnzyme
         * @return self
         */
        public function removeHeureEnzyme(HeureEnzyme $heureEnzyme): self
        {
            $this->_heureEnzymes->removeElement($heureEnzyme);
            return $this;
        }

        /**
         * Retourne la collection des décanteurs céréales associées à l'OF.
         *
         * @return Collection
         */
        public function getDecanteurCereales(): Collection
        {
            return $this->_decanteurCereales;
        }

        /**
         * Ajoute un décanteur céréales à l'OF.
         *
         * @param DecanteurCereales $decanteurCereale
         * @return self
         */
        public function addDecanteurCereale(DecanteurCereales $decanteurCereale): self
        {
            if (!$this->_decanteurCereales->contains($decanteurCereale)) {
                $this->_decanteurCereales[] = $decanteurCereale;
            }
            return $this;
        }

        /**
         * Retire un décanteur céréales de l'OF.
         *
         * @param DecanteurCereales $decanteurCereale
         * @return self
         */
        public function removeDecanteurCereale(DecanteurCereales $decanteurCereale): self
        {
            $this->_decanteurCereales->removeElement($decanteurCereale);
            return $this;
        }

        /**
         * Retourne la collection des analyses soja associées à l'OF.
         *
         * @return Collection
         */
        public function getAnalyseSojas(): Collection
        {
            return $this->_analyseSojas;
        }

        /**
         * Ajoute une analyse soja à l'OF.
         *
         * @param AnalyseSoja $analyseSoja
         * @return self
         */
        public function addAnalyseSoja(AnalyseSoja $analyseSoja): self
        {
            if (!$this->_analyseSojas->contains($analyseSoja)) {
                $this->_analyseSojas[] = $analyseSoja;
            }
            return $this;
        }

        /**
         * Retire une analyse soja de l'OF.
         *
         * @param AnalyseSoja $analyseSoja
         * @return self
         */
        public function removeAnalyseSoja(AnalyseSoja $analyseSoja): self
        {
            $this->_analyseSojas->removeElement($analyseSoja);
            return $this;
        }

        /**
         * Retourne l'okara associé à l'OF.
         *
         * @return Okara|null
         */
        public function getOkara(): ?Okara
        {
            return $this->_okara;
        }

        /**
         * Définit l'okara associé à l'OF.
         *
         * @param Okara|null $okara
         * @return self
         */
        public function setOkara(?Okara $okara): self
        {
            $this->_okara = $okara;
            return $this;
        }

        /**
         * Retourne le HACCP associé à l'OF.
         *
         * @return HACCP|null
         */
        public function getHaccp(): ?HACCP
        {
            return $this->_haccp;
        }

        /**
         * Définit le HACCP associé à l'OF.
         *
         * @param HACCP|null $haccp
         * @return self
         */
        public function setHaccp(?HACCP $haccp): self
        {
            $this->_haccp = $haccp;
            return $this;
        }

        /**
         * Retourne l'AvCorrectSoja associé à l'OF.
         *
         * @return AvCorrectSoja|null
         */
        public function getAvCorrectSoja(): ?AvCorrectSoja
        {
            return $this->_avCorrectSoja;
        }

        /**
         * Définit l'AvCorrectSoja associé à l'OF.
         *
         * @param AvCorrectSoja|null $avCorrectSoja
         * @return self
         */
        public function setAvCorrectSoja(?AvCorrectSoja $avCorrectSoja): self
        {
            $this->_avCorrectSoja = $avCorrectSoja;
            return $this;
        }

        /**
         * Retourne l'AvCorrectCereales associé à l'OF.
         *
         * @return AvCorrectCereales|null
         */
        public function getAvCorrectCereales(): ?AvCorrectCereales
        {
            return $this->_avCorrectCereales;
        }

        /**
         * Définit l'AvCorrectCereales associé à l'OF.
         *
         * @param AvCorrectCereales|null $avCorrectCereales
         * @return self
         */
        public function setAvCorrectCereales(?AvCorrectCereales $avCorrectCereales): self
        {
            $this->_avCorrectCereales = $avCorrectCereales;
            return $this;
        }

        /**
         * Retourne l'ApCorrectCereales associé à l'OF.
         *
         * @return ApCorrectCereales|null
         */
        public function getApCorrectCereales(): ?ApCorrectCereales
        {
            return $this->_apCorrectCereales;
        }

        /**
         * Définit l'ApCorrectCereales associé à l'OF.
         *
         * @param ApCorrectCereales|null $apCorrectCereales
         * @return self
         */
        public function setApCorrectCereales(?ApCorrectCereales $apCorrectCereales): self
        {
            $this->_apCorrectCereales = $apCorrectCereales;
            return $this;
        }

        /**
         * Retourne l'ApCorrectSoja associé à l'OF.
         *
         * @return ApCorrectSoja|null
         */
        public function getApCorrectSoja(): ?ApCorrectSoja
        {
            return $this->_apCorrectSoja;
        }

        /**
         * Définit l'ApCorrectSoja associé à l'OF.
         *
         * @param ApCorrectSoja|null $apCorrectSoja
         * @return self
         */
        public function setApCorrectSoja(?ApCorrectSoja $apCorrectSoja): self
        {
            $this->_apCorrectSoja = $apCorrectSoja;
            return $this;
        }
}
