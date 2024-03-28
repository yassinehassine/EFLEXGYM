<?php

namespace App\Entity;

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


}
