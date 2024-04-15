<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProgrammePersonnalise
 *
 * @ORM\Table(name="programme_personnalise")
 * @ORM\Entity
 */
class ProgrammePersonnalise
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
     * @ORM\Column(name="objectif", type="string", length=255, nullable=false)
     */
    private $objectif;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Exercice", inversedBy="idProgramme")
     * @ORM\JoinTable(name="programme_exercice",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_programme", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_exercice", referencedColumnName="id")
     *   }
     * )
     */
    private $idExercice = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idExercice = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjectif(): ?string
    {
        return $this->objectif;
    }

    public function setObjectif(string $objectif): static
    {
        $this->objectif = $objectif;

        return $this;
    }

    /**
     * @return Collection<int, Exercice>
     */
    public function getIdExercice(): Collection
    {
        return $this->idExercice;
    }

    public function addIdExercice(Exercice $idExercice): static
    {
        if (!$this->idExercice->contains($idExercice)) {
            $this->idExercice->add($idExercice);
        }

        return $this;
    }

    public function removeIdExercice(Exercice $idExercice): static
    {
        $this->idExercice->removeElement($idExercice);

        return $this;
    }

}
