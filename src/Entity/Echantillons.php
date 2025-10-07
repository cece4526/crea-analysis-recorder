<?php

namespace App\Entity;

use App\Repository\EchantillonsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

/**
 * Entité Echantillons
 */
#[ORM\Entity(repositoryClass: EchantillonsRepository::class)]
#[ORM\Table(name: 'echantillons')]
class Echantillons
{
    /**
     * Extrait sec de l'échantillon
     */
    #[ORM\Column(name: 'extrait_sec', type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $_extrait_sec = null;

    /**
     * Retourne l'extrait sec de l'échantillon.
     *
     * @return string|null
     */
    /**
     * Retourne l'extrait sec de l'échantillon.
     *
     * @return string|null L'extrait sec
     */
    public function getExtraitSec(): ?string
    {
        return $this->_extrait_sec;
    }

    /**
     * Définit l'extrait sec de l'échantillon.
     *
     * @param string|null $extrait_sec Valeur de l'extrait sec
     *
     * @return self Retourne l'instance courante
     */
    public function setExtraitSec(?string $extrait_sec): self
    {
        $this->_extrait_sec = $extrait_sec;
        return $this;
    }
    /**
     * Identifiant unique de l'échantillon
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    private ?int $_id = null;

    /**
     * Poids initial (étape 0)
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_poids_0 = null;

    /**
     * Poids intermédiaire (étape 1)
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_poids_1 = null;

    /**
     * Poids final (étape 2)
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_poids_2 = null;

    /**
     * Retourne l'identifiant de l'échantillon.
     *
     * @return int|null L'identifiant
     */
    public function getId(): ?int
    {
        return $this->_id;
    }

    /**
     * Retourne le poids initial (étape 0).
     *
     * @return string|null Le poids 0
     */
    public function getPoids0(): ?string
    {
        return $this->_poids_0;
    }

    /**
     * Définit le poids initial (étape 0).
     *
     * @param string|null $poids_0 Valeur du poids 0
     *
     * @return self Retourne l'instance courante
     */
    public function setPoids0(?string $poids_0): self
    {
        $this->_poids_0 = $poids_0;
        return $this;
    }

    /**
     * Retourne le poids intermédiaire (étape 1).
     *
     * @return string|null Le poids 1
     */
    public function getPoids1(): ?string
    {
        return $this->_poids_1;
    }

    /**
     * Définit le poids intermédiaire (étape 1).
     *
     * @param string|null $poids_1 Valeur du poids 1
     *
     * @return self Retourne l'instance courante
     */
    public function setPoids1(?string $poids_1): self
    {
        $this->_poids_1 = $poids_1;
        return $this;
    }

    /**
     * Retourne le poids final (étape 2).
     *
     * @return string|null Le poids 2
     */
    public function getPoids2(): ?string
    {
        return $this->_poids_2;
    }

    /**
     * Définit le poids final (étape 2).
     *
     * @param string|null $poids_2 Valeur du poids 2
     *
     * @return self Retourne l'instance courante
     */
    public function setPoids2(?string $poids_2): self
    {
        $this->_poids_2 = $poids_2;
        return $this;
    }

    /**
     * @ORM\ManyToOne(targetEntity=Okara::class, inversedBy="echantillons")
     * @ORM\JoinColumn(nullable=false)
     */
    /**
     * Okara associé à l'échantillon
     */
    #[ORM\ManyToOne(targetEntity: Okara::class, inversedBy: 'echantillons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Okara $_okara = null;

    /**
     * Retourne l'okara associé à l'échantillon.
     *
     * @return Okara|null
     */
    public function getOkara(): ?Okara
    {
        return $this->_okara;
    }

    /**
     * Définit l'okara associé à l'échantillon.
     *
     * @param Okara|null $okara Instance d'Okara à associer
     *
     * @return self Retourne l'instance courante
     */
    public function setOkara(?Okara $okara): self
    {
        $this->_okara = $okara;
        return $this;
    }
}
