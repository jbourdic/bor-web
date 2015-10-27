<?php

namespace BOR\GamificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QcmPoint
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BOR\GamificationBundle\Entity\QcmPointRepository")
 */
class QcmPoint
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
     * @var integer
     *
     * @ORM\Column(name="qcmPoints", type="integer")
     */
    private $qcmPoints;

    /**
     * @var integer
     *
     * @ORM\Column(name="experience", type="integer")
     */
    private $experience;


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
     * Set qcmPoints
     *
     * @param integer $qcmPoints
     *
     * @return QcmPoint
     */
    public function setQcmPoints($qcmPoints)
    {
        $this->qcmPoints = $qcmPoints;

        return $this;
    }

    /**
     * Get qcmPoints
     *
     * @return integer 
     */
    public function getQcmPoints()
    {
        return $this->qcmPoints;
    }

    /**
     * Set experience
     *
     * @param integer $experience
     *
     * @return QcmPoint
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return integer 
     */
    public function getExperience()
    {
        return $this->experience;
    }
}
