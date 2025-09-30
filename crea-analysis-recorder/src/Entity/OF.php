<?php

namespace App\Entity;

use App\Repository\OFRepository;
use Doctrine\ORM\Mapping as ORM;

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
    private ?Production $production = null;


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
