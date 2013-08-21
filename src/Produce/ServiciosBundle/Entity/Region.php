<?php

namespace Produce\ServiciosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Region
 *
 * @ORM\Table(name="Region")
 * @ORM\Entity
 */
class Region
{
    /**
     * @var integer
     *
     * @ORM\Column(name="RegionID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $regionid;

    /**
     * @var string
     *
     * @ORM\Column(name="RegionDescription", type="string", length=50, nullable=false)
     */
    private $regiondescription;



    /**
     * Get regionid
     *
     * @return integer 
     */
    public function getRegionid()
    {
        return $this->regionid;
    }

    /**
     * Set regiondescription
     *
     * @param string $regiondescription
     * @return Region
     */
    public function setRegiondescription($regiondescription)
    {
        $this->regiondescription = $regiondescription;
    
        return $this;
    }

    /**
     * Get regiondescription
     *
     * @return string 
     */
    public function getRegiondescription()
    {
        return $this->regiondescription;
    }
}