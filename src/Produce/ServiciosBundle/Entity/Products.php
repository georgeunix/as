<?php

namespace Produce\ServiciosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Products
 *
 * @ORM\Table(name="Products")
 * @ORM\Entity
 */
class Products
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ProductID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $productid;

    /**
     * @var string
     *
     * @ORM\Column(name="ProductName", type="string", length=40, nullable=false)
     */
    private $productname;

    /**
     * @var string
     *
     * @ORM\Column(name="QuantityPerUnit", type="string", length=20, nullable=true)
     */
    private $quantityperunit;

    /**
     * @var integer
     *
     * @ORM\Column(name="UnitPrice", type="integer", nullable=true)
     */
    private $unitprice;

    /**
     * @var integer
     *
     * @ORM\Column(name="UnitsInStock", type="smallint", nullable=true)
     */
    private $unitsinstock;

    /**
     * @var integer
     *
     * @ORM\Column(name="UnitsOnOrder", type="smallint", nullable=true)
     */
    private $unitsonorder;

    /**
     * @var integer
     *
     * @ORM\Column(name="ReorderLevel", type="smallint", nullable=true)
     */
    private $reorderlevel;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Discontinued", type="boolean", nullable=false)
     */
    private $discontinued;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Orders", mappedBy="productid")
     */
    private $orderid;

    /**
     * @var \Categories
     *
     * @ORM\ManyToOne(targetEntity="Categories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CategoryID", referencedColumnName="CategoryID")
     * })
     */
    private $categoryid;

    /**
     * @var \Suppliers
     *
     * @ORM\ManyToOne(targetEntity="Suppliers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SupplierID", referencedColumnName="SupplierID")
     * })
     */
    private $supplierid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orderid = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

    /**
     * Get productid
     *
     * @return integer 
     */
    public function getProductid()
    {
        return $this->productid;
    }

    /**
     * Set productname
     *
     * @param string $productname
     * @return Products
     */
    public function setProductname($productname)
    {
        $this->productname = $productname;
    
        return $this;
    }

    /**
     * Get productname
     *
     * @return string 
     */
    public function getProductname()
    {
        return $this->productname;
    }

    /**
     * Set quantityperunit
     *
     * @param string $quantityperunit
     * @return Products
     */
    public function setQuantityperunit($quantityperunit)
    {
        $this->quantityperunit = $quantityperunit;
    
        return $this;
    }

    /**
     * Get quantityperunit
     *
     * @return string 
     */
    public function getQuantityperunit()
    {
        return $this->quantityperunit;
    }

    /**
     * Set unitprice
     *
     * @param integer $unitprice
     * @return Products
     */
    public function setUnitprice($unitprice)
    {
        $this->unitprice = $unitprice;
    
        return $this;
    }

    /**
     * Get unitprice
     *
     * @return integer 
     */
    public function getUnitprice()
    {
        return $this->unitprice;
    }

    /**
     * Set unitsinstock
     *
     * @param integer $unitsinstock
     * @return Products
     */
    public function setUnitsinstock($unitsinstock)
    {
        $this->unitsinstock = $unitsinstock;
    
        return $this;
    }

    /**
     * Get unitsinstock
     *
     * @return integer 
     */
    public function getUnitsinstock()
    {
        return $this->unitsinstock;
    }

    /**
     * Set unitsonorder
     *
     * @param integer $unitsonorder
     * @return Products
     */
    public function setUnitsonorder($unitsonorder)
    {
        $this->unitsonorder = $unitsonorder;
    
        return $this;
    }

    /**
     * Get unitsonorder
     *
     * @return integer 
     */
    public function getUnitsonorder()
    {
        return $this->unitsonorder;
    }

    /**
     * Set reorderlevel
     *
     * @param integer $reorderlevel
     * @return Products
     */
    public function setReorderlevel($reorderlevel)
    {
        $this->reorderlevel = $reorderlevel;
    
        return $this;
    }

    /**
     * Get reorderlevel
     *
     * @return integer 
     */
    public function getReorderlevel()
    {
        return $this->reorderlevel;
    }

    /**
     * Set discontinued
     *
     * @param boolean $discontinued
     * @return Products
     */
    public function setDiscontinued($discontinued)
    {
        $this->discontinued = $discontinued;
    
        return $this;
    }

    /**
     * Get discontinued
     *
     * @return boolean 
     */
    public function getDiscontinued()
    {
        return $this->discontinued;
    }

    /**
     * Add orderid
     *
     * @param \Produce\ServiciosBundle\Entity\Orders $orderid
     * @return Products
     */
    public function addOrderid(\Produce\ServiciosBundle\Entity\Orders $orderid)
    {
        $this->orderid[] = $orderid;
    
        return $this;
    }

    /**
     * Remove orderid
     *
     * @param \Produce\ServiciosBundle\Entity\Orders $orderid
     */
    public function removeOrderid(\Produce\ServiciosBundle\Entity\Orders $orderid)
    {
        $this->orderid->removeElement($orderid);
    }

    /**
     * Get orderid
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrderid()
    {
        return $this->orderid;
    }

    /**
     * Set categoryid
     *
     * @param \Produce\ServiciosBundle\Entity\Categories $categoryid
     * @return Products
     */
    public function setCategoryid(\Produce\ServiciosBundle\Entity\Categories $categoryid = null)
    {
        $this->categoryid = $categoryid;
    
        return $this;
    }

    /**
     * Get categoryid
     *
     * @return \Produce\ServiciosBundle\Entity\Categories 
     */
    public function getCategoryid()
    {
        return $this->categoryid;
    }

    /**
     * Set supplierid
     *
     * @param \Produce\ServiciosBundle\Entity\Suppliers $supplierid
     * @return Products
     */
    public function setSupplierid(\Produce\ServiciosBundle\Entity\Suppliers $supplierid = null)
    {
        $this->supplierid = $supplierid;
    
        return $this;
    }

    /**
     * Get supplierid
     *
     * @return \Produce\ServiciosBundle\Entity\Suppliers 
     */
    public function getSupplierid()
    {
        return $this->supplierid;
    }
}