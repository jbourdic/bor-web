<?php

namespace BOR\AdvertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BlockAdvert
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BOR\AdvertBundle\Entity\BlockAdvertRepository")
 */
class BlockAdvert
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
     * @ORM\Column(name="reason", type="string", length=500)
     */
    private $reason;

    /**
     * @var integer
     *
     * @ORM\Column(name="idAdvert", type="integer")
     */
    private $idAdvert;

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
     * Set reason
     *
     * @param string $reason
     *
     * @return BlockAdvert
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return string 
     */
    public function getReason()
    {
        return $this->reason;
    }


    /**
     * Set idAdvert
     *
     * @param integer $idAdvert
     *
     * @return BlockAdvert
     */
    public function setIdAdvert($idAdvert)
    {
        $this->idAdvert = $idAdvert;

        return $this;
    }

    /**
     * Get idAdvert
     *
     * @return integer 
     */
    public function getIdAdvert()
    {
        return $this->idAdvert;
    }
}
