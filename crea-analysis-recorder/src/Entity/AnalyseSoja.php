
<?php

namespace App\Entity;

use App\Entity\OF;

use App\Repository\AnalyseSojaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class AnalyseSoja
 *
 * @category Entity
 * @package  App\Entity
 * @author   VotreNom <votre@email.com>
 * @license  MIT
 * @link     https://votreprojet.com
 *
 * @ORM\Entity(repositoryClass=AnalyseSojaRepository::class)
 */
class AnalyseSoja
{
    /**
     * @ORM\ManyToOne(targetEntity=OF::class, inversedBy="analyseSojas")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?OF $of = null;

    public function getOf(): ?OF
    {
        return $this->of;
    }

    public function setOf(?OF $of): self
    {
        $this->of = $of;
        return $this;
    }
    /**
     * Identifiant unique
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $_id = null;

    /**
     * Litrage décantation
     *
     * @ORM\Column(type="integer")
     */
    private ?int $_litrage_decan = null;

    /**
     * Température broyage
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_temperature_broyage = null;

    /**
     * Eau
     *
     * @ORM\Column(type="integer")
     */
    private ?int $_eau = null;

    /**
     * Matière
     *
     * @ORM\Column(type="integer")
     */
    private ?int $_matiere = null;

    /**
     * ES avant décantation
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_es_av_decan = null;

    /**
     * ES après décantation
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_es_ap_decan = null;

    /**
     * Contrôle visuel
     *
     * @ORM\Column(type="boolean")
     */
    private ?bool $_control_visuel = null;

    /**
     * Débit bicarbonate
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_debit_bicar = null;

    /**
     * Vitesse différencielle
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_vitesse_diff = null;

    /**
     * Couple
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_couple = null;

    /**
     * Variponds
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_variponds = null;

    /**
     * Contre pression
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $_contre_pression = null;

    /**
     * Initial pilote
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $_initial_pilote = null;

    /**
     * Date de l'analyse
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    /**
     * Date de l'analyse
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $_date = null;

    /**
     * Retourne la date de l'analyse.
     *
     * @return \DateTimeInterface|null
     */
    /**
     * Retourne la date de l'analyse.
     *
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->_date;
    }

    /**
     * Définit la date de l'analyse.
     *
     *  @param \DateTimeInterface|null $date La date à définir pour l'analyse
     *
     *  @return self Retourne l'instance courante
     */
    public function setDate(?\DateTimeInterface $date): self
    {
        $this->_date = $date;
        return $this;
    }
    /**
     * Retourne le litrage décanté.
     *
     * @return int|null
     */
    public function getLitrageDecan(): ?int
    {
        return $this->_litrage_decan;
    }

    /**
     * Définit le litrage décanté.
     *
     * @param int|null $litrage_decan Valeur du litrage décanté
     * @return self Retourne l'instance courante
     */
    public function setLitrageDecan(?int $litrage_decan): self
    {
        $this->_litrage_decan = $litrage_decan;
        return $this;
    }

    /**
     * Retourne la température de broyage.
     *
     * @return string|null
     */
    public function getTemperatureBroyage(): ?string
    {
        return $this->_temperature_broyage;
    }

    /**
     * Définit la température de broyage.
     *
     * @param string|null $temperature_broyage Température de broyage à définir
     * @return self Retourne l'instance courante
     */
    public function setTemperatureBroyage(?string $temperature_broyage): self
    {
        $this->_temperature_broyage = $temperature_broyage;
        return $this;
    }

    /**
     * Retourne la quantité d'eau.
     *
     * @return int|null
     */
    public function getEau(): ?int
    {
        return $this->_eau;
    }

    /**
     * Définit la quantité d'eau.
     *
     * @param int|null $eau Quantité d'eau à définir
     * @return self Retourne l'instance courante
     */
    public function setEau(?int $eau): self
    {
        $this->_eau = $eau;
        return $this;
    }

    /**
     * Retourne la quantité de matière.
     *
     * @return int|null
     */
    public function getMatiere(): ?int
    {
        return $this->_matiere;
    }

    /**
     * Définit la quantité de matière.
     *
     * @param int|null $matiere Quantité de matière à définir
     * @return self Retourne l'instance courante
     */
    public function setMatiere(?int $matiere): self
    {
        $this->_matiere = $matiere;
        return $this;
    }

    /**
     * Retourne l'ES avant décantation.
     *
     * @return string|null
     */
    public function getEsAvDecan(): ?string
    {
        return $this->_es_av_decan;
    }

    /**
     * Définit l'ES avant décantation.
     *
     * @param string|null $es_av_decan Valeur de l'ES avant décantation
     * @return self Retourne l'instance courante
     */
    public function setEsAvDecan(?string $es_av_decan): self
    {
        $this->_es_av_decan = $es_av_decan;
        return $this;
    }

    /**
     * Retourne l'ES après décantation.
     *
     * @return string|null
     */
    public function getEsApDecan(): ?string
    {
        return $this->_es_ap_decan;
    }

    /**
     * Définit l'ES après décantation.
     *
     * @param string|null $es_ap_decan Valeur de l'ES après décantation
     * @return self Retourne l'instance courante
     */
    public function setEsApDecan(?string $es_ap_decan): self
    {
        $this->_es_ap_decan = $es_ap_decan;
        return $this;
    }
    {
        $this->_es_ap_decan = $es_ap_decan;
        return $this;
    }

    /**
     * Indique si le contrôle visuel a été effectué.
     *
     * @return bool|null
     */
    public function isControlVisuel(): ?bool
    {
        return $this->_control_visuel;
    }
    /**
     * Définit si le contrôle visuel a été effectué.
     *
     * @param bool|null $control_visuel
     * @return self
     */
    public function setControlVisuel(?bool $control_visuel): self
    {
        $this->_control_visuel = $control_visuel;
        return $this;
    }

    /**
     * Retourne le débit de bicarbonate.
     *
     * @return string|null
     */
    public function getDebitBicar(): ?string
    {
        return $this->_debit_bicar;
    }
    /**
     * Définit le débit de bicarbonate.
     *
     * @param string|null $debit_bicar
     * @return self
     */
    public function setDebitBicar(?string $debit_bicar): self
    {
        $this->_debit_bicar = $debit_bicar;
        return $this;
    }

    /**
     * Retourne la vitesse de diffusion.
     *
     * @return string|null
     */
    public function getVitesseDiff(): ?string
    {
        return $this->_vitesse_diff;
    }
    /**
     * Définit la vitesse de diffusion.
     *
     * @param string|null $vitesse_diff
     * @return self
     */
    public function setVitesseDiff(?string $vitesse_diff): self
    {
        $this->_vitesse_diff = $vitesse_diff;
        return $this;
    }

    public function getCouple(): ?string
    {
        return $this->_couple;
    }
    public function setCouple(?string $couple): self
    {
        $this->_couple = $couple;
        return $this;
    }

    public function getVariponds(): ?string
    {
        return $this->_variponds;
    }
    public function setVariponds(?string $variponds): self
    {
        $this->_variponds = $variponds;
        return $this;
    }

    public function getContrePression(): ?string
    {
        return $this->_contre_pression;
    }
    public function setContrePression(?string $contre_pression): self
    {
        $this->_contre_pression = $contre_pression;
        return $this;
    }

    public function getInitialPilote(): ?string
    {
        return $this->_initial_pilote;
    }
    public function setInitialPilote(?string $initial_pilote): self
    {
        $this->_initial_pilote = $initial_pilote;
        return $this;
    }
}
