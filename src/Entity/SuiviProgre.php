<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SuiviProgre
 *
 * @ORM\Table(name="suivi_progre", indexes={@ORM\Index(name="fk_suivi_adherent", columns={"id_adherent"})})
 * @ORM\Entity
 */
class SuiviProgre
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
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     */
    private $prenom;

    /**
     * @var float
     *
     * @ORM\Column(name="taille", type="float", precision=10, scale=0, nullable=false)
     */
    private $taille;

    /**
     * @var float
     *
     * @ORM\Column(name="poid", type="float", precision=10, scale=0, nullable=false)
     */
    private $poid;

    /**
     * @var float
     *
     * @ORM\Column(name="tour_de_taille", type="float", precision=10, scale=0, nullable=false)
     */
    private $tourDeTaille;

    /**
     * @var float
     *
     * @ORM\Column(name="tour_de_hanche", type="float", precision=10, scale=0, nullable=false)
     */
    private $tourDeHanche;

    /**
     * @var float
     *
     * @ORM\Column(name="IMC", type="float", precision=10, scale=0, nullable=false)
     */
    private $imc;

    /**
     * @var float
     *
     * @ORM\Column(name="IMG", type="float", precision=10, scale=0, nullable=false)
     */
    private $img;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=0, nullable=false)
     */
    private $sexe;

    /**
     * @var \Adherent
     *
     * @ORM\ManyToOne(targetEntity="Adherent")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_adherent", referencedColumnName="Id")
     * })
     */
    private $idAdherent;


}
