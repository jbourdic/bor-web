<?php

namespace BOR\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Sponsorship
 *
 * @package BOR\UserBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="Sponsorship")
 */
class Sponsorship
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
     * @var integer $idSponsor
     *
     * @ORM\Column(name="id_sponsor", type="integer")
     */
    protected $idSponsor;

    /**
     * @var integer $godsonEmail
     *
     * @ORM\Column(name="godson_email", type="string")
     */
    protected $godsonEmail;

    /**
     * @var boolean $hasAccepted
     *
     * @ORM\Column(name="has_accepted", type="boolean")
     */
    protected $hasAccepted = false;

    /**
     * @var datetime $createdOn
     *
     * @ORM\Column(name="created_on", type="datetime")
     */
    protected $createdOn;

    /**
     * @var datetime $updatedOn
     *
     * @ORM\Column(name="updated_on", type="datetime")
     */
    protected $updatedOn;

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
     * Set idSponsor
     *
     * @param integer $idSponsor
     *
     * @return Sponsorship
     */
    public function setIdSponsor($idSponsor)
    {
        $this->idSponsor = $idSponsor;

        return $this;
    }

    /**
     * Get idSponsor
     *
     * @return integer 
     */
    public function getIdSponsor()
    {
        return $this->idSponsor;
    }

    /**
     * Set godsonEmail
     *
     * @param string $godsonEmail
     *
     * @return Sponsorship
     */
    public function setGodsonEmail($godsonEmail)
    {
        $this->godsonEmail = $godsonEmail;

        return $this;
    }

    /**
     * Get godsonEmail
     *
     * @return string 
     */
    public function getGodsonEmail()
    {
        return $this->godsonEmail;
    }

    /**
     * Set hasAccepted
     *
     * @param boolean $hasAccepted
     *
     * @return Sponsorship
     */
    public function setHasAccepted($hasAccepted)
    {
        $this->hasAccepted = $hasAccepted;

        return $this;
    }

    /**
     * Get hasAccepted
     *
     * @return boolean 
     */
    public function getHasAccepted()
    {
        return $this->hasAccepted;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return Sponsorship
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
     * Set updatedOn
     *
     * @param \DateTime $updatedOn
     *
     * @return Sponsorship
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
}
