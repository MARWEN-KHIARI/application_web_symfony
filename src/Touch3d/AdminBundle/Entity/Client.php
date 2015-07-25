<?php

namespace Touch3d\AdminBundle\Entity;
use Touch3d\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Client
 *
 * @ORM\Table(name="t3d_client")
 * @ORM\Entity(repositoryClass="Touch3d\AdminBundle\Entity\ClientRepository")
 */
class Client
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
        $this->user   = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @ORM\OneToOne(targetEntity="\Touch3d\UserBundle\Entity\User", inversedBy="client")
     * @ORM\JoinColumn(name="idUser", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $user;

    /**
     * Set user
     * @param User $user
     * @return User
     */

    public function setUser(\Touch3d\UserBundle\Entity\User $user)
    {
        //$this->user[] = $user;
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
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $first_name;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $last_name;

    /**
     * @var string
     *
     * @ORM\Column(name="business_name", type="string", length=255, nullable=true)
     */
    private $business_name;


    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=30, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=30, nullable=true)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=100)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="postal_code", type="string", length=255)
     */
    private $postal_code;


    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;



    /**
     * @var boolean
     *
     * @ORM\Column(name="organization", type="boolean", nullable=true)
     */
    private $organization;

    /**
     * @var string
     *
     * @ORM\Column(name="organization_name", type="string", length=255, nullable=true)
     */
    private $organization_name;

    /**
     * @var string
     *
     * @ORM\Column(name="organization_abbreviation", type="string", length=255, nullable=true)
     */
    private $organization_abbreviation;

    /**
     * @var string
     *
     * @ORM\Column(name="registration_number", type="string", length=255, nullable=true)
     */
    private $registration_number;

    /**
     * @var string
     *
     * @ORM\Column(name="tax_identification_number", type="string", length=255, nullable=true)
     */
    private $tax_identification_number;


    /**
     * @var string
     *
     * @ORM\Column(name="skype", type="string", length=255, nullable=true)
     */
    private $skype;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean")
     */
    private $actif;


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
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $tax_identification_number
     */
    public function setTaxIdentificationNumber($tax_identification_number)
    {
        $this->tax_identification_number = $tax_identification_number;
    }

    /**
     * @return string
     */
    public function getTaxIdentificationNumber()
    {
        return $this->tax_identification_number;
    }

    /**
     * @param string $business_name
     */
    public function setBusinessName($business_name)
    {
        $this->business_name = $business_name;
    }

    /**
     * @return string
     */
    public function getBusinessName()
    {
        return $this->business_name;
    }

    /**
     * @param string $skype
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
    }

    /**
     * @return string
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * @param string $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param string $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param boolean $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }

    /**
     * @return boolean
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param string $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $organization_abbreviation
     */
    public function setOrganizationAbbreviation($organization_abbreviation)
    {
        $this->organization_abbreviation = $organization_abbreviation;
    }

    /**
     * @return string
     */
    public function getOrganizationAbbreviation()
    {
        return $this->organization_abbreviation;
    }

    /**
     * @param string $organization_name
     */
    public function setOrganizationName($organization_name)
    {
        $this->organization_name = $organization_name;
    }

    /**
     * @return string
     */
    public function getOrganizationName()
    {
        return $this->organization_name;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $postal_code
     */
    public function setPostalCode($postal_code)
    {
        $this->postal_code = $postal_code;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * @param string $registration_number
     */
    public function setRegistrationNumber($registration_number)
    {
        $this->registration_number = $registration_number;
    }

    /**
     * @return string
     */
    public function getRegistrationNumber()
    {
        return $this->registration_number;
    }

    /**
     * @param string $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param boolean $actif
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
    }

    /**
     * @return boolean
     */
    public function getActif()
    {
        return $this->actif;
    }

}
