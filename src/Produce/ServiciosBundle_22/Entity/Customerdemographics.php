<?php

namespace Produce\ServiciosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customerdemographics
 *
 * @ORM\Table(name="CustomerDemographics")
 * @ORM\Entity
 */
class Customerdemographics
{
    /**
     * @var string
     *
     * @ORM\Column(name="CustomerTypeID", type="string", length=10, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $customertypeid;

    /**
     * @var string
     *
     * @ORM\Column(name="CustomerDesc", type="text", nullable=true)
     */
    private $customerdesc;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Customers", mappedBy="customertypeid")
     */
    private $customerid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customerid = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

    /**
     * Get customertypeid
     *
     * @return string 
     */
    public function getCustomertypeid()
    {
        return $this->customertypeid;
    }

    /**
     * Set customerdesc
     *
     * @param string $customerdesc
     * @return Customerdemographics
     */
    public function setCustomerdesc($customerdesc)
    {
        $this->customerdesc = $customerdesc;
    
        return $this;
    }

    /**
     * Get customerdesc
     *
     * @return string 
     */
    public function getCustomerdesc()
    {
        return $this->customerdesc;
    }

    /**
     * Add customerid
     *
     * @param \Produce\ServiciosBundle\Entity\Customers $customerid
     * @return Customerdemographics
     */
    public function addCustomerid(\Produce\ServiciosBundle\Entity\Customers $customerid)
    {
        $this->customerid[] = $customerid;
    
        return $this;
    }

    /**
     * Remove customerid
     *
     * @param \Produce\ServiciosBundle\Entity\Customers $customerid
     */
    public function removeCustomerid(\Produce\ServiciosBundle\Entity\Customers $customerid)
    {
        $this->customerid->removeElement($customerid);
    }

    /**
     * Get customerid
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomerid()
    {
        return $this->customerid;
    }
}