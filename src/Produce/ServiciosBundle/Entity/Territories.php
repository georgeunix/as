<?php

namespace Produce\ServiciosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Territories
 *
 * @ORM\Table(name="Territories")
 * @ORM\Entity
 */
class Territories
{
    /**
     * @var string
     *
     * @ORM\Column(name="TerritoryID", type="string", length=20, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $territoryid;

    /**
     * @var string
     *
     * @ORM\Column(name="TerritoryDescription", type="string", length=50, nullable=false)
     */
    private $territorydescription;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Employees", mappedBy="territoryid")
     */
    private $employeeid;

    /**
     * @var \Region
     *
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="RegionID", referencedColumnName="RegionID")
     * })
     */
    private $regionid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->employeeid = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

    /**
     * Get territoryid
     *
     * @return string 
     */
    public function getTerritoryid()
    {
        return $this->territoryid;
    }

    /**
     * Set territorydescription
     *
     * @param string $territorydescription
     * @return Territories
     */
    public function setTerritorydescription($territorydescription)
    {
        $this->territorydescription = $territorydescription;
    
        return $this;
    }

    /**
     * Get territorydescription
     *
     * @return string 
     */
    public function getTerritorydescription()
    {
        return $this->territorydescription;
    }

    /**
     * Add employeeid
     *
     * @param \Produce\ServiciosBundle\Entity\Employees $employeeid
     * @return Territories
     */
    public function addEmployeeid(\Produce\ServiciosBundle\Entity\Employees $employeeid)
    {
        $this->employeeid[] = $employeeid;
    
        return $this;
    }

    /**
     * Remove employeeid
     *
     * @param \Produce\ServiciosBundle\Entity\Employees $employeeid
     */
    public function removeEmployeeid(\Produce\ServiciosBundle\Entity\Employees $employeeid)
    {
        $this->employeeid->removeElement($employeeid);
    }

    /**
     * Get employeeid
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmployeeid()
    {
        return $this->employeeid;
    }

    /**
     * Set regionid
     *
     * @param \Produce\ServiciosBundle\Entity\Region $regionid
     * @return Territories
     */
    public function setRegionid(\Produce\ServiciosBundle\Entity\Region $regionid = null)
    {
        $this->regionid = $regionid;
    
        return $this;
    }

    /**
     * Get regionid
     *
     * @return \Produce\ServiciosBundle\Entity\Region 
     */
    public function getRegionid()
    {
        return $this->regionid;
    }
}