<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="fk_relationuser", columns={"id_user"}), @ORM\Index(name="fk_reservationplaning", columns={"id_planing"})})
 * @ORM\Entity(repositoryClass=App\Repository\Repository::class)
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_reservation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idReservation;

    /**
     * @var int
     *
     * @ORM\Column(name="id_planing", type="integer", nullable=false)
     */
    private $idPlaning;

    /**
     * @var string
     *
     * @ORM\Column(name="num_tell", type="string", length=255, nullable=false)
     */
    private $numTell;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;

    public function getIdReservation(): ?int
    {
        return $this->idReservation;
    }

    public function getIdPlaning(): ?int
    {
        return $this->idPlaning;
    }

    public function setIdPlaning(int $idPlaning): static
    {
        $this->idPlaning = $idPlaning;

        return $this;
    }

    public function getNumTell(): ?string
    {
        return $this->numTell;
    }

    public function setNumTell(string $numTell): static
    {
        $this->numTell = $numTell;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }


}
