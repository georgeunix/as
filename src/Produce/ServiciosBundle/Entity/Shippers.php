<?php

namespace Produce\ServiciosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Shippers
 *
 * @ORM\Table(name="Shippers")
 * @ORM\Entity
 */
class Shippers
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ShipperID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $shipperid;

    /**
     * @var string
     *
     * @ORM\Column(name="CompanyName", type="string", length=40, nullable=false)
     */
    private $companyname;

    /**
     * @var string
     *
     * @ORM\Column(name="Phone", type="string", length=24, nullable=true)
     */
    private $phone;



    /**
     * Get shipperid
     *
     * @return integer 
     */
    public function getShipperid()
    {
        return $this->shipperid;
    }

    /**
     * Set companyname
     *
     * @param string $companyname
     * @return Shippers
     */
    public function setCompanyname($companyname)
    {
        $this->companyname = $companyname;
    
        return $this;
    }

    /**
     * Get companyname
     *
     * @return string 
     */
    public function getCompanyname()
    {
        return $this->companyname;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Shippers
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }
}