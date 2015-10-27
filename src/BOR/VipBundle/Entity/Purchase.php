<?php

namespace BOR\VipBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Purchase
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BOR\VipBundle\Entity\PurchaseRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Purchase
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
     * @ORM\ManyToOne(targetEntity="BOR\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="orderNumber", type="integer")
     */
    private $orderNumber;

    /**
     * @ORM\ManyToOne(targetEntity="BOR\VipBundle\Entity\Package")
     * @ORM\JoinColumn(nullable=true)
     */
    private $package;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="orderDate", type="datetime")
     */
    private $orderDate;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(name="priceIva", type="float")
     */
    private $priceIva;

    /**
     * @var float
     *
     * @ORM\Column(name="taxAmount", type="float")
     */
    private $taxAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="invoiceZipCode", type="string", length=10)
     */
    private $invoiceZipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="invoiceStreet", type="string", length=255)
     */
    private $invoiceStreet;

    /**
     * @var string
     *
     * @ORM\Column(name="invoiceCity", type="string", length=255)
     */
    private $invoiceCity;

    /**
     * @var string
     *
     * @ORM\Column(name="invoiceCountry", type="string", length=255)
     */
    private $invoiceCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="invoiceFirstname", type="string", length=255)
     */
    private $invoiceFirstname;

    /**
     * @var string
     *
     * @ORM\Column(name="invoiceLastname", type="string", length=255)
     */
    private $invoiceLastname;

    /**
     * @var string
     *
     * @ORM\Column(name="transactionId", type="string", length=255)
     */
    private $transactionId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdOn", type="datetime")
     */
    private $createdOn;


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
     * Set orderNumber
     *
     * @param integer $orderNumber
     *
     * @return Purchase
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * Get orderNumber
     *
     * @return integer
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * Set orderDate
     *
     * @param \DateTime $orderDate
     *
     * @return Purchase
     */
    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * Get orderDate
     *
     * @return \DateTime
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Purchase
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set priceIva
     *
     * @param float $priceIva
     *
     * @return Purchase
     */
    public function setPriceIva($priceIva)
    {
        $this->priceIva = $priceIva;

        return $this;
    }

    /**
     * Get priceIva
     *
     * @return float
     */
    public function getPriceIva()
    {
        return $this->priceIva;
    }

    /**
     * Set taxAmount
     *
     * @param float $taxAmount
     *
     * @return Purchase
     */
    public function setTaxAmount($taxAmount)
    {
        $this->taxAmount = $taxAmount;

        return $this;
    }

    /**
     * Get taxAmount
     *
     * @return float
     */
    public function getTaxAmount()
    {
        return $this->taxAmount;
    }

    /**
     * Set invoiceZipCode
     *
     * @param string $invoiceZipCode
     *
     * @return Purchase
     */
    public function setInvoiceZipCode($invoiceZipCode)
    {
        $this->invoiceZipCode = $invoiceZipCode;

        return $this;
    }

    /**
     * Get invoiceZipCode
     *
     * @return string
     */
    public function getInvoiceZipCode()
    {
        return $this->invoiceZipCode;
    }

    /**
     * Set invoiceStreet
     *
     * @param string $invoiceStreet
     *
     * @return Purchase
     */
    public function setInvoiceStreet($invoiceStreet)
    {
        $this->invoiceStreet = $invoiceStreet;

        return $this;
    }

    /**
     * Get invoiceStreet
     *
     * @return string
     */
    public function getInvoiceStreet()
    {
        return $this->invoiceStreet;
    }

    /**
     * Set invoiceCity
     *
     * @param string $invoiceCity
     *
     * @return Purchase
     */
    public function setInvoiceCity($invoiceCity)
    {
        $this->invoiceCity = $invoiceCity;

        return $this;
    }

    /**
     * Get invoiceCity
     *
     * @return string
     */
    public function getInvoiceCity()
    {
        return $this->invoiceCity;
    }

    /**
     * Set invoiceCountry
     *
     * @param string $invoiceCountry
     *
     * @return Purchase
     */
    public function setInvoiceCountry($invoiceCountry)
    {
        $this->invoiceCountry = $invoiceCountry;

        return $this;
    }

    /**
     * Get invoiceCountry
     *
     * @return string
     */
    public function getInvoiceCountry()
    {
        return $this->invoiceCountry;
    }

    /**
     * Set invoiceFirstname
     *
     * @param string $invoiceFirstname
     *
     * @return Purchase
     */
    public function setInvoiceFirstname($invoiceFirstname)
    {
        $this->invoiceFirstname = $invoiceFirstname;

        return $this;
    }

    /**
     * Get invoiceFirstname
     *
     * @return string
     */
    public function getInvoiceFirstname()
    {
        return $this->invoiceFirstname;
    }

    /**
     * Set invoiceLastname
     *
     * @param string $invoiceLastname
     *
     * @return Purchase
     */
    public function setInvoiceLastname($invoiceLastname)
    {
        $this->invoiceLastname = $invoiceLastname;

        return $this;
    }

    /**
     * Get invoiceLastname
     *
     * @return string
     */
    public function getInvoiceLastname()
    {
        return $this->invoiceLastname;
    }

    /**
     * Set transactionId
     *
     * @param string $transactionId
     *
     * @return Purchase
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get transactionId
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return Purchase
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Get createdOn
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Set user
     *
     * @param \BOR\UserBundle\Entity\User $user
     * 
     * @return Purchase
     */
    public function setUser(\BOR\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \BOR\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set package
     *
     * @param \BOR\VipBundle\Entity\Package $package
     * 
     * @return Purchase
     */
    public function setPackage(\BOR\VipBundle\Entity\Package $package = null)
    {
        $this->package = $package;

        return $this;
    }

    /**
     * Get package
     *
     * @return \BOR\VipBundle\Entity\Package
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedOnValue()
    {
        $this->createdOn = new \DateTime();
        $this->orderDate = new \DateTime();
    }
}
