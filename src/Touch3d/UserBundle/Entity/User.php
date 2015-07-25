<?php

namespace Touch3d\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="t3d_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        //$this->client = new ArrayCollection();
        $this->commandes_user = new ArrayCollection();
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
     * @ORM\OneToOne(targetEntity="\Touch3d\AdminBundle\Entity\Client", mappedBy="user")
     * @ORM\JoinColumn(name="idClient", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $client;



    /**
     * Set client
     * @param Client $client
     * @return Client
     */

    public function setClient(\Touch3d\AdminBundle\Entity\Client $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return Touch3d\AdminBundle\Entity\Client
     */

    public function getClient()
    {
        return $this->client;
    }




    /**
     * @ORM\OneToMany(targetEntity="\Touch3d\AdminBundle\Entity\Commande", mappedBy="user")
     */
    protected $commandes_user;

    public function getCommandes_user()
    {
        return $this->commandes_user;
    }



    /**
     * @ORM\OneToMany(targetEntity="\Touch3d\AdminBundle\Entity\Facture", mappedBy="user")
     */
    protected $facture_user;

    public function getFacture_user()
    {
        return $this->facture_user;
    }




    /**
     * @var boolean
     *
     * @ORM\Column(name="commercial", type="boolean", nullable=true)
     */
    private $commercial;

    /**
     * Set commercial
     *
     * @param boolean $commercial
     * @return User
     */
    public function setCommercial($commercial)
    {
        $this->commercial = $commercial;
        return $this;
    }

    /**
     * Get commercial
     *
     * @return boolean
     */
    public function getCommercial()
    {
        return $this->commercial;
    }


}
