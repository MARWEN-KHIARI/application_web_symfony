<?php

namespace Touch3d\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Commande
 *
 * @ORM\Table(name="T3D_commande")
 * @ORM\Entity
 */

class Commande
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
        //$this->produit = new ArrayCollection();
        //$this->user = new ArrayCollection();
    }


    /**
     * @ORM\ManyToOne(targetEntity="Produit", inversedBy="commandes_produit")
     * @ORM\JoinColumn(name="idProduit", referencedColumnName="id")
     */
    protected  $produit;


    /**
     * Set produit
     *
     * @param Produit $produit
     * @return Produit
     */
    public function setProduit(\Touch3d\AdminBundle\Entity\Produit $produit)
    {
        $this->produit = $produit;
        return $this;
    }

    /**
     * @return Touch3d\AdminBundle\Entity\Produit
     */
    public function getProduit()
    {
        return $this->produit;
    }




    /**
     * @ORM\ManyToOne(targetEntity="\Touch3d\UserBundle\Entity\User", inversedBy="commandes_user")
     * @ORM\JoinColumn(name="idUser", referencedColumnName="id", onDelete="CASCADE")
     */
    protected  $user;


    /**
     * Set user
     *
     * @param User $user
     * @return User
     */
    public function setUser(\Touch3d\UserBundle\Entity\User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Touch3d\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * Set quantite
     *
     * @param integer $quantite
     * @return Commande
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer
     */
    public function getQuantite()
    {
        return $this->quantite;
    }



    /**
     * @ORM\ManyToOne(targetEntity="\Touch3d\AdminBundle\Entity\Facture", inversedBy="commandes_facture")
     * @ORM\JoinColumn(name="idFacture", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected  $facture;


    /**
     * Set facture
     *
     * @param Facture $facture
     * @return Facture
     */
    public function setFacture(\Touch3d\AdminBundle\Entity\Facture $facture)
    {
        $this->facture = $facture;
        return $this;
    }

    /**
     * @return Touch3d\AdminBundle\Entity\Facture
     */
    public function getFacture()
    {
        return $this->facture;
    }

}
