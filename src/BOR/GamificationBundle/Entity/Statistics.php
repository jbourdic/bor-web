<?php

namespace BOR\GamificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statistics
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BOR\GamificationBundle\Entity\StatisticsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Statistics
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
    private $level = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="experience", type="integer")
     */
    private $experience = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="levelExperience", type="integer")
     */
    private $levelExperience = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="credit", type="integer")
     */
    private $credit = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="qcm", type="integer")
     */
    private $qcm = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="courses", type="integer")
     */
    private $courses = 0;

    /**
     * @ORM\OneToOne(targetEntity="BOR\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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
     * Set idUser
     *
     * @param integer $idUser
     *
     * @return Statistics
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return integer
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return Statistics
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
     * Set experience
     *
     * @param integer $experience
     *
     * @return Statistics
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

    /**
     * Set credit
     *
     * @param integer $credit
     *
     * @return Statistics
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return integer
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set qcm
     *
     * @param integer $qcm
     *
     * @return Statistics
     */
    public function setQcm($qcm)
    {
        $this->qcm = $qcm;

        return $this;
    }

    /**
     * Get qcm
     *
     * @return integer
     */
    public function getQcm()
    {
        return $this->qcm;
    }

    /**
     * Set courses
     *
     * @param integer $courses
     *
     * @return Statistics
     */
    public function setCourses($courses)
    {
        $this->courses = $courses;

        return $this;
    }

    /**
     * Get courses
     *
     * @return integer
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return Statistics
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
     * @return Statistics
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
     * Set levelExperience
     *
     * @param integer $levelExperience
     *
     * @return Statistics
     */
    public function setLevelExperience($levelExperience)
    {
        $this->levelExperience = $levelExperience;

        return $this;
    }

    /**
     * Get levelExperience
     *
     * @return integer
     */
    public function getLevelExperience()
    {
        return $this->levelExperience;
    }

    /**
     * Set user
     *
     * @param \BOR\UserBundle\Entity\User $user
     *
     * @return Statistics
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
}
