<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * BilanFinancier
 *
 * @ORM\Table(name="bilan_financier")
 * @ORM\Entity
 */
class BilanFinancier
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
     * @var float
     *
     * @ORM\Column(name="salaires_coachs", type="float", precision=10, scale=0, nullable=false)
     */
    private $salairesCoachs;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_location", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixLocation;

    /**
     * @var float
     *
     * @ORM\Column(name="revenus_abonnements", type="float", precision=10, scale=0, nullable=false)
     */
    private $revenusAbonnements;

    /**
     * @var float
     *
     * @ORM\Column(name="revenus_produits", type="float", precision=10, scale=0, nullable=false)
     */
    private $revenusProduits;

    /**
     * @var float
     *
     * @ORM\Column(name="depenses", type="float", precision=10, scale=0, nullable=false)
     */
    private $depenses;

    /**
     * @var float
     *
     * @ORM\Column(name="profit", type="float", precision=10, scale=0, nullable=false)
     */
    private $profit;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSalairesCoachs(): ?float
    {
        return $this->salairesCoachs;
    }

    public function setSalairesCoachs(float $salairesCoachs): static
    {
        $this->salairesCoachs = $salairesCoachs;

        return $this;
    }

    public function getPrixLocation(): ?float
    {
        return $this->prixLocation;
    }

    public function setPrixLocation(float $prixLocation): static
    {
        $this->prixLocation = $prixLocation;

        return $this;
    }

    public function getRevenusAbonnements(): ?float
    {
        return $this->revenusAbonnements;
    }

    public function setRevenusAbonnements(float $revenusAbonnements): static
    {
        $this->revenusAbonnements = $revenusAbonnements;

        return $this;
    }

    public function getRevenusProduits(): ?float
    {
        return $this->revenusProduits;
    }

    public function setRevenusProduits(float $revenusProduits): static
    {
        $this->revenusProduits = $revenusProduits;

        return $this;
    }

    public function getDepenses(): ?float
    {
        return $this->depenses;
    }

    public function setDepenses(float $depenses): static
    {
        $this->depenses = $depenses;

        return $this;
    }

    public function getProfit(): ?float
    {
        return $this->profit;
    }

    public function setProfit(float $profit): static
    {
        $this->profit = $profit;

        return $this;
    }


}
