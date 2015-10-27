<?php

namespace BOR\AdvertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Favorite
 *
 * @package BOR\AdverBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="Favorite")
 * @ORM\HasLifecycleCallbacks()
 */
class Favorite
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="BOR\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="BOR\AdvertBundle\Entity\Advert")
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     */
    protected $advert;

    /**
     * @var datetime $createdOn
     *
     * @ORM\Column(name="created_on", type="datetime")
     */
    protected $createdOn;


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
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return Favorite
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
     * Constructor
     */
    public function __construct()
    {
        $this->idUser = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set advert
     *
     * @param \BOR\AdvertBundle\Entity\Advert $advert
     *
     * @return Favorite
     */
    public function setAdvert(\BOR\AdvertBundle\Entity\Advert $advert = null)
    {
        $this->advert = $advert;

        return $this;
    }

    /**
     * Get advert
     *
     * @return \BOR\AdvertBundle\Entity\Advert
     */
    public function getAdvert()
    {
        return $this->advert;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdOn = new \DateTime();

    }

    /**
     * Set user
     *
     * @param \BOR\UserBundle\Entity\User $user
     *
     * @return Favorite
     */
    public function setUser(\BOR\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }
}
