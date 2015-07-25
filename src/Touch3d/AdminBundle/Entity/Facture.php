<?php

namespace Touch3d\AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facture
 *
 * @ORM\Table(name="T3D_facture")
 * @ORM\Entity
 */

class Facture
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


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="date")
     */
    private $dateCreation;


    /**
     * @return Facture
     */
    public function setDateCreation()
    {
        $this->dateCreation = new \Datetime();
        $this->setStatus("facture non payée");
        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }



    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePaiement", type="date", nullable=true)
     */
    private $datePaiement;


    /**
     * @return Facture
     */
    public function setDatePaiement()
    {
        $this->datePaiement = new \Datetime();
        $this->setStatus("facture payée");
        return $this;
    }

    /**
     * Get datePaiement
     *
     * @return \DateTime
     */
    public function getDatePaiement()
    {
        return $this->datePaiement;
    }






    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=100)
     */
    private $status;


    /**
     * Set status
     *
     * @param string $status
     * @return Facture
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


    public function __construct()
    {
        $this->commandes_facture = new ArrayCollection();
    }
    /**
     * @ORM\OneToMany(targetEntity="\Touch3d\AdminBundle\Entity\Commande", mappedBy="facture")
     */

    protected $commandes_facture;

    public function getCommandes_facture()
    {
        return $this->commandes_facture;
    }


    public function addCommande(\Touch3d\AdminBundle\Entity\Commande $commande)
    {
        $this->commande[] = $commande;
    }

    public function removeCommande(\Touch3d\AdminBundle\Entity\Commande $commande)
    {
        $this->commande->removeElement($commande);
    }



    /**
     * @ORM\ManyToOne(targetEntity="\Touch3d\UserBundle\Entity\User", inversedBy="facture_user")
     * @ORM\JoinColumn(name="idClient", referencedColumnName="id", nullable=true, onDelete="CASCADE")
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
}
