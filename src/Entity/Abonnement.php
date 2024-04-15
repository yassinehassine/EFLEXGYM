<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Abonnement
 *
 * @ORM\Table(name="abonnement", indexes={@ORM\Index(name="fk_abonnement_adherent", columns={"id_adherent"}), @ORM\Index(name="fk_bllll11", columns={"id_bilan_financier"})})
 * @ORM\Entity
 */
class Abonnement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=0, nullable=false)
     */
    private $type;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="date", nullable=false)
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="date", nullable=false)
     */
    private $dateFin;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=0, nullable=false)
     */
    private $etat;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_adherent", referencedColumnName="id")
     * })
     */
    private $idAdherent;

    /**
     * @var \BilanFinancier
     *
     * @ORM\ManyToOne(targetEntity="BilanFinancier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_bilan_financier", referencedColumnName="id")
     * })
     */
    private $idBilanFinancier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getIdAdherent(): ?User
    {
        return $this->idAdherent;
    }

    public function setIdAdherent(?User $idAdherent): static
    {
        $this->idAdherent = $idAdherent;

        return $this;
    }

    public function getIdBilanFinancier(): ?BilanFinancier
    {
        return $this->idBilanFinancier;
    }

    public function setIdBilanFinancier(?BilanFinancier $idBilanFinancier): static
    {
        $this->idBilanFinancier = $idBilanFinancier;

        return $this;
    }


}
