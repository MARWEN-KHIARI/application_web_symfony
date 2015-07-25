<?php

namespace Touch3d\AdminBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Produit
 *
 * @ORM\Table(name="T3D_produits")
 * @ORM\Entity
 * @UniqueEntity(fields="nom", message="name of product is already in use")
 */

class Produit
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    public function __construct()
    {
        $this->categorie   = new \Doctrine\Common\Collections\ArrayCollection();
        $this->commandes_produit = new ArrayCollection();
    }
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;


    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=255)
     */
    private $img;


    /**
     * @var string
     *
     * @ORM\Column(name="resumer", type="text")
     */
    private $resumer;


    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=100)
     */
    private $status;

    /**
     * Set nom
     *
     * @param string $nom
     * @return Produit
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }


    /**
     * Set img
     *
     * @param string $img
     * @return Produit
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }


    /**
     * Set resumer
     *
     * @param string $resumer
     * @return Produit
     */
    public function setResumer($resumer)
    {
        $this->resumer = $resumer;

        return $this;
    }

    /**
     * Get resumer
     *
     * @return string
     */
    public function getResumer()
    {
        return $this->resumer;
    }


    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Produit
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Produit
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }



    /**
     * @var integer
     *
     * @ORM\Column(name="qteStock", type="integer")
     */
    private $qteStock;

    /**
     * Set qteStock
     *
     * @param integer $qteStock
     * @return Produit
     */
    public function setQteStock($qteStock)
    {
        $this->qteStock = $qteStock;

        return $this;
    }

    /**
     * Get qteStock
     *
     * @return integer
     */
    public function getQteStock()
    {
        return $this->qteStock;
    }


    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=2)
     */
    private $prix;

    /**
     * Set prix
     *
     * @param float $prix
     * @return Produit
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }


    /**
     * @ORM\ManyToOne(targetEntity="Fournisseur", inversedBy="fournisseur_produit")
     * @ORM\JoinColumn(name="fournisseur_id", referencedColumnName="id", nullable=true)
     */
    private $fournisseur;

    /**
     * Set fournisseur
     *
     * @param Fournisseur $fournisseur
     * @return Fournisseur
     */
    public function setFournisseur($fournisseur)
    {
        $this->fournisseur = $fournisseur;
        return $this;
    }

    /**
     * @return Touch3d\AdminBundle\Entity\Fournisseur
     */
    public function getFournisseur()
    {
        return $this->fournisseur;
    }



    /**
     * @ORM\ManyToOne(targetEntity="Categorie", inversedBy="produit")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $categorie;

    /**
     * Set categorie
     * @param Categorie $categorie
     * @return Categorie
     */
    public function setCategorie(\Touch3d\AdminBundle\Entity\Categorie $categorie)
    {
        $this->categorie = $categorie;
        return $this;
    }

    /**
     * @return Touch3d\AdminBundle\Entity\Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }



    /**
     * @ORM\OneToMany(targetEntity="Commande", mappedBy="produit")
     */
    protected $commandes_produit;

    public function getCommandes_produit()
    {
        return $this->commandes_produit;
    }
}
