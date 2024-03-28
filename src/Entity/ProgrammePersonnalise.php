<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProgrammePersonnalise
 *
 * @ORM\Table(name="programme_personnalise", indexes={@ORM\Index(name="fk_add_exercice", columns={"id_exercice"})})
 * @ORM\Entity
 */
class ProgrammePersonnalise
{
    /**
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description_objectif", type="string", length=255, nullable=false)
     */
    private $descriptionObjectif;

    /**
     * @var \Exercice
     *
     * @ORM\ManyToOne(targetEntity="Exercice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_exercice", referencedColumnName="id")
     * })
     */
    private $idExercice;

    /**
     * @var \Exercice
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Exercice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $id;


}
