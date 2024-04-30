<?php

namespace App\Entity;

// src/Entity/Evenement.php

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement", indexes={@ORM\Index(name="fk_type", columns={"type"})})
 * @ORM\Entity
 */
class Evenement
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
     * @ORM\Column(name="event_name", type="string", length=255, nullable=false)
     */
    private $eventName;

    /**
     * @var Date
     *
     * @ORM\Column(name="date_debut", type="date", nullable=false, options={"default"="current_timestamp()"})
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual("today")
     */
    private $dateDebut = 'current_timestamp()';

    /**
     * @var Date
     *
     * @ORM\Column(name="date_fin", type="datetime", nullable=false, options={"default"="current_timestamp()"})
     * @Assert\NotBlank
     * @Assert\Expression("this.getDateFin() > this.getDateDebut()")
     */
    private $dateFin = 'current_timestamp()';

    /**
     * @var int
     *
     * @ORM\Column(name="duration", type="integer", nullable=false)
     * @Assert\NotBlank(groups={"date_equal"})
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="place", type="string", length=255, nullable=false)
     */
    private $place;

    /**
     * @var Type
     *
     * @ORM\ManyToOne(targetEntity="Type")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type", referencedColumnName="id")
     * })
     */
    private $type;

    // Getters and setters for all properties



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function setEventName(string $eventName): static
    {
        $this->eventName = $eventName;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut instanceof \DateTimeInterface ? $this->dateDebut : null;
    }
    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }
    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin instanceof \DateTimeInterface ? $this->dateFin : null;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): static
    {
        $this->type = $type;

        return $this;
    }


}