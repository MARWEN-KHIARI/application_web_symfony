<?php

namespace Touch3d\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Categorie
 *
 * @ORM\Table(name="t3d_categorie")
 * @ORM\Entity
 */

class Categorie
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    public function __construct()
    {
        $this->produit = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;



    /**
     * @param string $nom
     * @return Categorie
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @ORM\OneToMany(targetEntity="Produit", mappedBy="categorie")
     */
    private $produit;

    public function getProduit()
    {
        return $this->produit;
    }
/*
    public function addProduit(\Touch3d\AdminBundle\Entity\Produit $produit)
    {
        $this->produit[] = $produit;
    }

    public function removeProduit(\Touch3d\AdminBundle\Entity\Produit $produit)
    {
        $this->produit->removeElement($produit);
    }

*/

}
