<?php

namespace Produce\ServiciosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Employees
 *
 * @ORM\Table(name="Employees")
 * @ORM\Entity
 */
class Employees
{
    /**
     * @var integer
     *
     * @ORM\Column(name="EmployeeID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $employeeid;

    /**
     * @var string
     *
     * @ORM\Column(name="LastName", type="string", length=20, nullable=false)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="FirstName", type="string", length=10, nullable=false)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="Title", type="string", length=30, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="TitleOfCourtesy", type="string", length=25, nullable=true)
     */
    private $titleofcourtesy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="BirthDate", type="datetime", nullable=true)
     */
    private $birthdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="HireDate", type="datetime", nullable=true)
     */
    private $hiredate;

    /**
     * @var string
     *
     * @ORM\Column(name="Address", type="string", length=60, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="City", type="string", length=15, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="Region", type="string", length=15, nullable=true)
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="PostalCode", type="string", length=10, nullable=true)
     */
    private $postalcode;

    /**
     * @var string
     *
     * @ORM\Column(name="Country", type="string", length=15, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="HomePhone", type="string", length=24, nullable=true)
     */
    private $homephone;

    /**
     * @var string
     *
     * @ORM\Column(name="Extension", type="string", length=4, nullable=true)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="Photo", type="text", nullable=true)
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="Notes", type="text", nullable=true)
     */
    private $notes;

    /**
     * @var string
     *
     * @ORM\Column(name="PhotoPath", type="string", length=255, nullable=true)
     */
    private $photopath;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Territories", inversedBy="employeeid")
     * @ORM\JoinTable(name="employeeterritories",
     *   joinColumns={
     *     @ORM\JoinColumn(name="EmployeeID", referencedColumnName="EmployeeID")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="TerritoryID", referencedColumnName="TerritoryID")
     *   }
     * )
     */
    private $territoryid;

    /**
     * @var \Employees
     *
     * @ORM\ManyToOne(targetEntity="Employees")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ReportsTo", referencedColumnName="EmployeeID")
     * })
     */
    private $reportsto;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->territoryid = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

    /**
     * Get employeeid
     *
     * @return integer 
     */
    public function getEmployeeid()
    {
        return $this->employeeid;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Employees
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return Employees
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Employees
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set titleofcourtesy
     *
     * @param string $titleofcourtesy
     * @return Employees
     */
    public function setTitleofcourtesy($titleofcourtesy)
    {
        $this->titleofcourtesy = $titleofcourtesy;
    
        return $this;
    }

    /**
     * Get titleofcourtesy
     *
     * @return string 
     */
    public function getTitleofcourtesy()
    {
        return $this->titleofcourtesy;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     * @return Employees
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    
        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime 
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set hiredate
     *
     * @param \DateTime $hiredate
     * @return Employees
     */
    public function setHiredate($hiredate)
    {
        $this->hiredate = $hiredate;
    
        return $this;
    }

    /**
     * Get hiredate
     *
     * @return \DateTime 
     */
    public function getHiredate()
    {
        return $this->hiredate;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Employees
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Employees
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set region
     *
     * @param string $region
     * @return Employees
     */
    public function setRegion($region)
    {
        $this->region = $region;
    
        return $this;
    }

    /**
     * Get region
     *
     * @return string 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set postalcode
     *
     * @param string $postalcode
     * @return Employees
     */
    public function setPostalcode($postalcode)
    {
        $this->postalcode = $postalcode;
    
        return $this;
    }

    /**
     * Get postalcode
     *
     * @return string 
     */
    public function getPostalcode()
    {
        return $this->postalcode;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Employees
     */
    public function setCountry($country)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set homephone
     *
     * @param string $homephone
     * @return Employees
     */
    public function setHomephone($homephone)
    {
        $this->homephone = $homephone;
    
        return $this;
    }

    /**
     * Get homephone
     *
     * @return string 
     */
    public function getHomephone()
    {
        return $this->homephone;
    }

    /**
     * Set extension
     *
     * @param string $extension
     * @return Employees
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    
        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set photo
     *
     * @param string $photo
     * @return Employees
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    
        return $this;
    }

    /**
     * Get photo
     *
     * @return string 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return Employees
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    
        return $this;
    }

    /**
     * Get notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set photopath
     *
     * @param string $photopath
     * @return Employees
     */
    public function setPhotopath($photopath)
    {
        $this->photopath = $photopath;
    
        return $this;
    }

    /**
     * Get photopath
     *
     * @return string 
     */
    public function getPhotopath()
    {
        return $this->photopath;
    }

    /**
     * Add territoryid
     *
     * @param \Produce\ServiciosBundle\Entity\Territories $territoryid
     * @return Employees
     */
    public function addTerritoryid(\Produce\ServiciosBundle\Entity\Territories $territoryid)
    {
        $this->territoryid[] = $territoryid;
    
        return $this;
    }

    /**
     * Remove territoryid
     *
     * @param \Produce\ServiciosBundle\Entity\Territories $territoryid
     */
    public function removeTerritoryid(\Produce\ServiciosBundle\Entity\Territories $territoryid)
    {
        $this->territoryid->removeElement($territoryid);
    }

    /**
     * Get territoryid
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTerritoryid()
    {
        return $this->territoryid;
    }

    /**
     * Set reportsto
     *
     * @param \Produce\ServiciosBundle\Entity\Employees $reportsto
     * @return Employees
     */
    public function setReportsto(\Produce\ServiciosBundle\Entity\Employees $reportsto = null)
    {
        $this->reportsto = $reportsto;
    
        return $this;
    }

    /**
     * Get reportsto
     *
     * @return \Produce\ServiciosBundle\Entity\Employees 
     */
    public function getReportsto()
    {
        return $this->reportsto;
    }
}