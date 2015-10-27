<?php

namespace BOR\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Slider
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BOR\CoreBundle\Entity\SliderRepository")
 */
class Slider
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
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=1000)
     */
    private $link;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=200)
     */
    private $text;

    /**
     * @ORM\OneToOne(targetEntity="BOR\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(nullable=true)
     */
    private $media;

    /**
     * @var integer
     *
     * @ORM\Column(name="slideOrder", type="integer")
     */
    private $slideOrder = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdOn", type="datetime", nullable=true)
     */
    private $createdOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedOn", type="datetime", nullable=true)
     */
    private $updatedOn;

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
     * Set link
     *
     * @param string $link
     *
     * @return Slider
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Slider
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param mixed $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Slider
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
     * Set createdOn
     *
     * @return Slider
     */
    public function setCreatedOn()
    {
        $this->createdOn = new \DateTime();

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
     * Set updatedOn
     *
     * @return Slider
     */
    public function setUpdatedOn()
    {
        $this->updatedOn = new \DateTime();

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
     * Set slideOrder
     *
     * @param integer $slideOrder
     *
     * @return Slider
     */
    public function setSlideOrder($slideOrder)
    {
        $this->slideOrder = $slideOrder;

        return $this;
    }

    /**
     * Get slideOrder
     *
     * @return integer 
     */
    public function getSlideOrder()
    {
        return $this->slideOrder;
    }

    /**
     * @ORM\PrePersist
     */
    public function onCreate()
    {
        $this->setCreatedOn();
        $this->setUpdatedOn();
        $this->media->setCreatedOn();
    }
}
