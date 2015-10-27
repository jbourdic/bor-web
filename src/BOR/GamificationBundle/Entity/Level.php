<?php

namespace BOR\GamificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Level
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BOR\GamificationBundle\Entity\LevelRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Level
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
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var integer
     *
     * @ORM\Column(name="experienceMin", type="integer")
     */
    private $experienceMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="experienceRequired", type="integer")
     * @Assert\GreaterThan(value="0", message="Cette valeur doit être supérieure à 0")
     */
    private $experienceRequired;

    /**
     * @var integer
     *
     * @ORM\Column(name="experienceMax", type="integer")
     * @Assert\GreaterThanOrEqual(value="experienceMin", message="Cette valeur doit être supérieure à expérience min")
     */
    private $experienceMax;

    /**
     * @var integer
     *
     * @ORM\Column(name="creditReward", type="integer")
     * @Assert\GreaterThanOrEqual(value="0", message="Cette valeur doit être supérieureà 0")
     */
    private $creditReward;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return Level
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set experienceMax
     *
     * @param integer $experienceMax
     *
     * @return Level
     */
    public function setExperienceMax($experienceMax)
    {
        $this->experienceMax = $experienceMax;

        return $this;
    }

    /**
     * Get experienceMax
     *
     * @return integer 
     */
    public function getExperienceMax()
    {
        return $this->experienceMax;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return Level
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
     * @return Level
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
     * Set experienceMin
     *
     * @param integer $experienceMin
     *
     * @return Level
     */
    public function setExperienceMin($experienceMin)
    {
        $this->experienceMin = $experienceMin;

        return $this;
    }

    /**
     * Get experienceMin
     *
     * @return integer 
     */
    public function getExperienceMin()
    {
        return $this->experienceMin;
    }

    /**
     * Set experienceRequired
     *
     * @param integer $experienceRequired
     * 
     * @return Level
     */
    public function setExperienceRequired($experienceRequired)
    {
        $this->experienceRequired = $experienceRequired;

        return $this;
    }

    /**
     * Get experienceRequired
     *
     * @return integer 
     */
    public function getExperienceRequired()
    {
        return $this->experienceRequired;
    }

    /**
     * Set creditReward
     *
     * @param integer $creditReward
     * 
     * @return Level
     */
    public function setCreditReward($creditReward)
    {
        $this->creditReward = $creditReward;

        return $this;
    }

    /**
     * Get creditReward
     *
     * @return integer 
     */
    public function getCreditReward()
    {
        return $this->creditReward;
    }
}
