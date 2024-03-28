<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit", indexes={@ORM\Index(name="fk_add_admin", columns={"id_admin"}), @ORM\Index(name="fk_bilan_financier2", columns={"id_bilan_financier"}), @ORM\Index(name="categorie_ibfk_1", columns={"categorie"})})
 * @ORM\Entity
 */
use App\Repository\ProduitRepository;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id= null;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

  
    #[ORM\Column]
    private ?float $prix=null;

  
    #[ORM\Column]
    private ?int $quantite=null;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="id_admin", type="integer", nullable=false)
     */
    private $idAdmin;

    /**
     * @var \Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie", referencedColumnName="id")
     * })
     */
    private $categorie;

    #[ORM\ManyToOne(targetEntity: BilanFinancier::class, inversedBy: 'produits')]
    #[ORM\JoinColumn(name: 'id_bilan_financier', referencedColumnName: 'id')]
    private ?BilanFinancier $idBilanFinancier = null;

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
