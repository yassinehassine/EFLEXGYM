<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Exercice
 *
 * @ORM\Table(name="exercice")
 * @ORM\Entity
 */
class Exercice
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
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrDeSet", type="integer", nullable=false)
     */
    private $nbrdeset;

    /**
     * @var string
     *
     * @ORM\Column(name="groupeMusculaire", type="string", length=255, nullable=false)
     */
    private $groupemusculaire;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrDeRepetitions", type="integer", nullable=false)
     */
    private $nbrderepetitions;

    /**
     * @var string
     *
     * @ORM\Column(name="categorieExercice", type="string", length=255, nullable=false)
     */
    private $categorieexercice;

    /**
     * @var string
     *
     * @ORM\Column(name="typeEquipement", type="string", length=255, nullable=false)
     */
    private $typeequipement;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="ProgrammePersonnalise", mappedBy="idExercice")
     */
    private $idProgramme = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idProgramme = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getNbrdeset(): ?int
    {
        return $this->nbrdeset;
    }

    public function setNbrdeset(int $nbrdeset): static
    {
        $this->nbrdeset = $nbrdeset;

        return $this;
    }

    public function getGroupemusculaire(): ?string
    {
        return $this->groupemusculaire;
    }

    public function setGroupemusculaire(string $groupemusculaire): static
    {
        $this->groupemusculaire = $groupemusculaire;

        return $this;
    }

    public function getNbrderepetitions(): ?int
    {
        return $this->nbrderepetitions;
    }

    public function setNbrderepetitions(int $nbrderepetitions): static
    {
        $this->nbrderepetitions = $nbrderepetitions;

        return $this;
    }

    public function getCategorieexercice(): ?string
    {
        return $this->categorieexercice;
    }

    public function setCategorieexercice(string $categorieexercice): static
    {
        $this->categorieexercice = $categorieexercice;

        return $this;
    }

    public function getTypeequipement(): ?string
    {
        return $this->typeequipement;
    }

    public function setTypeequipement(string $typeequipement): static
    {
        $this->typeequipement = $typeequipement;

        return $this;
    }

    /**
     * @return Collection<int, ProgrammePersonnalise>
     */
    public function getIdProgramme(): Collection
    {
        return $this->idProgramme;
    }

    public function addIdProgramme(ProgrammePersonnalise $idProgramme): static
    {
        if (!$this->idProgramme->contains($idProgramme)) {
            $this->idProgramme->add($idProgramme);
            $idProgramme->addIdExercice($this);
        }

        return $this;
    }

    public function removeIdProgramme(ProgrammePersonnalise $idProgramme): static
    {
        if ($this->idProgramme->removeElement($idProgramme)) {
            $idProgramme->removeIdExercice($this);
        }

        return $this;
    }

}
