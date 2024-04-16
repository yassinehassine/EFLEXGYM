<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Cours;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * Planning
 *
 * @ORM\Table(name="planning", indexes={@ORM\Index(name="fk_abonnement_cour", columns={"id_cour"}), @ORM\Index(name="fk_add_coach", columns={"id_coach"})})
 * @ORM\Entity(repositoryClass=App\Repository\PlanningRepository::class)
 */
class Planning
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
     * @ORM\Column(name="salle", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="La salle ne peut pas être vide.")
     */
    private $salle;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_place_max", type="integer", nullable=false)
     * @Assert\NotBlank(message="Le nombre de places maximum ne peut pas être vide.")

     */
    private $nbPlaceMax;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     * @Assert\GreaterThan("today", message="La date de début doit être future à aujourd'hui.")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="heure", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="L'heure ne peut pas être vide.")
     */
    private $heure;

   /**
 * @var Cours|null
 *
 * @ORM\ManyToOne(targetEntity=Cours::class)
 * @ORM\JoinColumn(name="id_cour", referencedColumnName="id")
 */
private $cour;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="id_coach", referencedColumnName="id")
     */
    private $coach;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalle(): ?string
    {
        return $this->salle;
    }

    public function setSalle(string $salle): static
    {
        $this->salle = $salle;

        return $this;
    }

    public function getNbPlaceMax(): ?int
    {
        return $this->nbPlaceMax;
    }

    public function setNbPlaceMax(int $nbPlaceMax): static
    {
        $this->nbPlaceMax = $nbPlaceMax;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getHeure(): ?string
    {
        return $this->heure;
    }

    public function setHeure(string $heure): static
    {
        $this->heure = $heure;

        return $this;
    }

    public function getCour(): ?Cours
    {
        return $this->cour;
    }
    

    public function setCour(?Cours $cour): static
    {
        $this->cour = $cour;

        return $this;
    }

    public function getCoach(): ?User
    {
        return $this->coach;
    }

    public function setCoach(?User $coach): static
    {
        $this->coach = $coach;

        return $this;
    }
}
