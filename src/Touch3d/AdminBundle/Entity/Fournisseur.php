<?php

namespace Touch3d\AdminBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Fournisseur
 *
 * @ORM\Table(name="T3D_fournisseurs")
 * @ORM\Entity
 * @UniqueEntity(fields="nom", message="name of product is already in use")
 */

class Fournisseur
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
        $this->fournisseur_produit = new ArrayCollection();
    }
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;


    /**
     * Set nom
     *
     * @param string $nom
     * @return Fournisseur
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
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;


    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Fournisseur
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }



    /**
     * @ORM\OneToMany(targetEntity="\Touch3d\AdminBundle\Entity\Produit", mappedBy="fournisseur")
     */
    protected $fournisseur_produit;

    public function getFournisseur_produit()
    {
        return $this->fournisseur_produit;
    }
    public function addProduit(\Touch3d\AdminBundle\Entity\Produit $produit)
    {
        $this->produit[] = $produit;
    }

    public function removeProduit(\Touch3d\AdminBundle\Entity\Produit $produit)
    {
        $this->produit->removeElement($produit);
    }
}
