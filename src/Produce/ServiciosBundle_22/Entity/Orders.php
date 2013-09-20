<?php

namespace Produce\ServiciosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table(name="Orders")
 * @ORM\Entity
 */
class Orders
{
    /**
     * @var integer
     *
     * @ORM\Column(name="OrderID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $orderid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="OrderDate", type="datetime", nullable=true)
     */
    private $orderdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="RequiredDate", type="datetime", nullable=true)
     */
    private $requireddate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ShippedDate", type="datetime", nullable=true)
     */
    private $shippeddate;

    /**
     * @var integer
     *
     * @ORM\Column(name="Freight", type="integer", nullable=true)
     */
    private $freight;

    /**
     * @var string
     *
     * @ORM\Column(name="ShipName", type="string", length=40, nullable=true)
     */
    private $shipname;

    /**
     * @var string
     *
     * @ORM\Column(name="ShipAddress", type="string", length=60, nullable=true)
     */
    private $shipaddress;

    /**
     * @var string
     *
     * @ORM\Column(name="ShipCity", type="string", length=15, nullable=true)
     */
    private $shipcity;

    /**
     * @var string
     *
     * @ORM\Column(name="ShipRegion", type="string", length=15, nullable=true)
     */
    private $shipregion;

    /**
     * @var string
     *
     * @ORM\Column(name="ShipPostalCode", type="string", length=10, nullable=true)
     */
    private $shippostalcode;

    /**
     * @var string
     *
     * @ORM\Column(name="ShipCountry", type="string", length=15, nullable=true)
     */
    private $shipcountry;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Products", inversedBy="orderid")
     * @ORM\JoinTable(name="order details",
     *   joinColumns={
     *     @ORM\JoinColumn(name="OrderID", referencedColumnName="OrderID")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="ProductID", referencedColumnName="ProductID")
     *   }
     * )
     */
    private $productid;

    /**
     * @var \Customers
     *
     * @ORM\ManyToOne(targetEntity="Customers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CustomerID", referencedColumnName="CustomerID")
     * })
     */
    private $customerid;

    /**
     * @var \Employees
     *
     * @ORM\ManyToOne(targetEntity="Employees")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="EmployeeID", referencedColumnName="EmployeeID")
     * })
     */
    private $employeeid;

    /**
     * @var \Shippers
     *
     * @ORM\ManyToOne(targetEntity="Shippers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ShipVia", referencedColumnName="ShipperID")
     * })
     */
    private $shipvia;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productid = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

    /**
     * Get orderid
     *
     * @return integer 
     */
    public function getOrderid()
    {
        return $this->orderid;
    }

    /**
     * Set orderdate
     *
     * @param \DateTime $orderdate
     * @return Orders
     */
    public function setOrderdate($orderdate)
    {
        $this->orderdate = $orderdate;
    
        return $this;
    }

    /**
     * Get orderdate
     *
     * @return \DateTime 
     */
    public function getOrderdate()
    {
        return $this->orderdate;
    }

    /**
     * Set requireddate
     *
     * @param \DateTime $requireddate
     * @return Orders
     */
    public function setRequireddate($requireddate)
    {
        $this->requireddate = $requireddate;
    
        return $this;
    }

    /**
     * Get requireddate
     *
     * @return \DateTime 
     */
    public function getRequireddate()
    {
        return $this->requireddate;
    }

    /**
     * Set shippeddate
     *
     * @param \DateTime $shippeddate
     * @return Orders
     */
    public function setShippeddate($shippeddate)
    {
        $this->shippeddate = $shippeddate;
    
        return $this;
    }

    /**
     * Get shippeddate
     *
     * @return \DateTime 
     */
    public function getShippeddate()
    {
        return $this->shippeddate;
    }

    /**
     * Set freight
     *
     * @param integer $freight
     * @return Orders
     */
    public function setFreight($freight)
    {
        $this->freight = $freight;
    
        return $this;
    }

    /**
     * Get freight
     *
     * @return integer 
     */
    public function getFreight()
    {
        return $this->freight;
    }

    /**
     * Set shipname
     *
     * @param string $shipname
     * @return Orders
     */
    public function setShipname($shipname)
    {
        $this->shipname = $shipname;
    
        return $this;
    }

    /**
     * Get shipname
     *
     * @return string 
     */
    public function getShipname()
    {
        return $this->shipname;
    }

    /**
     * Set shipaddress
     *
     * @param string $shipaddress
     * @return Orders
     */
    public function setShipaddress($shipaddress)
    {
        $this->shipaddress = $shipaddress;
    
        return $this;
    }

    /**
     * Get shipaddress
     *
     * @return string 
     */
    public function getShipaddress()
    {
        return $this->shipaddress;
    }

    /**
     * Set shipcity
     *
     * @param string $shipcity
     * @return Orders
     */
    public function setShipcity($shipcity)
    {
        $this->shipcity = $shipcity;
    
        return $this;
    }

    /**
     * Get shipcity
     *
     * @return string 
     */
    public function getShipcity()
    {
        return $this->shipcity;
    }

    /**
     * Set shipregion
     *
     * @param string $shipregion
     * @return Orders
     */
    public function setShipregion($shipregion)
    {
        $this->shipregion = $shipregion;
    
        return $this;
    }

    /**
     * Get shipregion
     *
     * @return string 
     */
    public function getShipregion()
    {
        return $this->shipregion;
    }

    /**
     * Set shippostalcode
     *
     * @param string $shippostalcode
     * @return Orders
     */
    public function setShippostalcode($shippostalcode)
    {
        $this->shippostalcode = $shippostalcode;
    
        return $this;
    }

    /**
     * Get shippostalcode
     *
     * @return string 
     */
    public function getShippostalcode()
    {
        return $this->shippostalcode;
    }

    /**
     * Set shipcountry
     *
     * @param string $shipcountry
     * @return Orders
     */
    public function setShipcountry($shipcountry)
    {
        $this->shipcountry = $shipcountry;
    
        return $this;
    }

    /**
     * Get shipcountry
     *
     * @return string 
     */
    public function getShipcountry()
    {
        return $this->shipcountry;
    }

    /**
     * Add productid
     *
     * @param \Produce\ServiciosBundle\Entity\Products $productid
     * @return Orders
     */
    public function addProductid(\Produce\ServiciosBundle\Entity\Products $productid)
    {
        $this->productid[] = $productid;
    
        return $this;
    }

    /**
     * Remove productid
     *
     * @param \Produce\ServiciosBundle\Entity\Products $productid
     */
    public function removeProductid(\Produce\ServiciosBundle\Entity\Products $productid)
    {
        $this->productid->removeElement($productid);
    }

    /**
     * Get productid
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductid()
    {
        return $this->productid;
    }

    /**
     * Set customerid
     *
     * @param \Produce\ServiciosBundle\Entity\Customers $customerid
     * @return Orders
     */
    public function setCustomerid(\Produce\ServiciosBundle\Entity\Customers $customerid = null)
    {
        $this->customerid = $customerid;
    
        return $this;
    }

    /**
     * Get customerid
     *
     * @return \Produce\ServiciosBundle\Entity\Customers 
     */
    public function getCustomerid()
    {
        return $this->customerid;
    }

    /**
     * Set employeeid
     *
     * @param \Produce\ServiciosBundle\Entity\Employees $employeeid
     * @return Orders
     */
    public function setEmployeeid(\Produce\ServiciosBundle\Entity\Employees $employeeid = null)
    {
        $this->employeeid = $employeeid;
    
        return $this;
    }

    /**
     * Get employeeid
     *
     * @return \Produce\ServiciosBundle\Entity\Employees 
     */
    public function getEmployeeid()
    {
        return $this->employeeid;
    }

    /**
     * Set shipvia
     *
     * @param \Produce\ServiciosBundle\Entity\Shippers $shipvia
     * @return Orders
     */
    public function setShipvia(\Produce\ServiciosBundle\Entity\Shippers $shipvia = null)
    {
        $this->shipvia = $shipvia;
    
        return $this;
    }

    /**
     * Get shipvia
     *
     * @return \Produce\ServiciosBundle\Entity\Shippers 
     */
    public function getShipvia()
    {
        return $this->shipvia;
    }
}