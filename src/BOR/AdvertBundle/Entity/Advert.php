<?php

namespace BOR\AdvertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use BOR\MediaBundle\Entity\Media;

/**
 * Advert
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BOR\AdvertBundle\Entity\AdvertRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Advert
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
     * @var \BOR\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="BOR\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=5000)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="lodgmentType", type="string", length=255)
     */
    private $lodgmentType;

    /**
     * @var float
     *
     * @ORM\Column(name="surface", type="float", nullable=true)
     */
    private $surface;

    /**
     * @var string
     *
     * @ORM\Column(name="transactType", type="string", length=255)
     */
    private $transactType;

    /**
     * @var float
     *
     * @ORM\Column(name="charges", type="float")
     */
    private $charges;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(name="tax", type="float")
     */
    private $tax;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="streetNumber", type="string", length=20)
     */
    private $streetNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="zipCode", type="string", length=10)
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=200)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=30)
     */
    private $country;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float")
     */
    private $lat;

    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="float")
     */
    private $lng;

    /**
     * @ORM\ManyToMany(targetEntity="BOR\MediaBundle\Entity\Media", cascade={"persist"})
     */
    private $medias;

    public $gallery0;
    public $gallery1;
    public $gallery2;
    public $gallery3;
    public $gallery4;

    public $upload0;
    public $upload1;
    public $upload2;
    public $upload3;
    public $upload4;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdOn", type="datetime")
     */
    private $createdOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedOn", type="datetime")
     */
    private $updatedOn;

    /**
     * @var string
     *
     * @ORM\Column(name="metaTitle", type="text", nullable=true)
     */
    private $metaTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="metaDescription", type="text", nullable=true)
     */
    private $metaDescription;
    
    /**
     * @var string
     *
     * @ORM\Column(name="photosphere", type="text", nullable=true)
     */
    private $photosphere = "";

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
     * Set title
     *
     * @param string $title
     *
     * @return Advert
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
     * Set description
     *
     * @param string $description
     *
     * @return Advert
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set lodgmentType
     *
     * @param string $lodgmentType
     *
     * @return Advert
     */
    public function setLodgmentType($lodgmentType)
    {
        $this->lodgmentType = $lodgmentType;

        return $this;
    }

    /**
     * Get lodgmentType
     *
     * @return string
     */
    public function getLodgmentType()
    {
        return $this->lodgmentType;
    }

    /**
     * Set transactType
     *
     * @param string $transactType
     *
     * @return Advert
     */
    public function setTransactType($transactType)
    {
        $this->transactType = $transactType;

        return $this;
    }

    /**
     * Get transactType
     *
     * @return string
     */
    public function getTransactType()
    {
        return $this->transactType;
    }

    /**
     * Set charges
     *
     * @param float $charges
     *
     * @return Advert
     */
    public function setCharges($charges)
    {
        $this->charges = $charges;

        return $this;
    }

    /**
     * Get charges
     *
     * @return float
     */
    public function getCharges()
    {
        return $this->charges;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Advert
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
     * Set tax
     *
     * @param float $tax
     *
     * @return Advert
     */
    public function setTax($tax)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * Get tax
     *
     * @return float
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return Advert
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set streetNumber
     *
     * @param string $streetNumber
     *
     * @return Advert
     */
    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    /**
     * Get streetNumber
     *
     * @return string
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     *
     * @return Advert
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Advert
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
     * Set country
     *
     * @param string $country
     *
     * @return Advert
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
     * Set lat
     *
     * @param float $lat
     *
     * @return Advert
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param float $lng
     *
     * @return Advert
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set media
     *
     * @param Media $media
     *
     * @return Advert
     */
    public function setMedia($media)
    {
        $this->media = $media;

        return $this;
    }


    /**
     * Add media
     *
     * @return Advert
     */
    public function addMedia(Media $media)
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * Remove media
     */
    public function removeMedia(Media $media)
    {
        $this->medias->removeElement($media);
    }

    /**
     * Remove All media
     */
    public function removeAllMedia()
    {
        $this->medias = new ArrayCollection();
    }

    /**
     * Get medias
     *
     * @return ArrayCollection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Advert
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set updatedOn
     *
     * @param \DateTime $updatedOn
     *
     * @return Advert
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Get updatedOn
     *
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return Advert
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
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdOn = new \DateTime();
        $this->updatedOn = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedOn = new \DateTime();
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     *
     * @return Advert
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Get metaTitle
     *
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     *
     * @return Advert
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set user
     *
     * @param \BOR\UserBundle\Entity\User $user
     *
     * @return Advert
     */
    public function setUser(\BOR\UserBundle\Entity\User $user = null)
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
     * Set photosphere
     *
     * @param string $photosphere
     *
     * @return Advert
     */
    public function setPhotosphere($photosphere)
    {
        $this->photosphere = $photosphere;

        return $this;
    }

    /**
     * Get photosphere
     *
     * @return string
     */
    public function getPhotosphere()
    {
        return $this->photosphere;
    }
    
    /**
     * construct
     */
    public function __construct()
    {
        $this->medias = new ArrayCollection();
    }

    /**
     * Set surface
     *
     * @param float $surface
     * @return Advert
     */
    public function setSurface($surface)
    {
        $this->surface = $surface;

        return $this;
    }

    /**
     * Get surface
     *
     * @return float 
     */
    public function getSurface()
    {
        return $this->surface;
    }
}
