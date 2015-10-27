<?php

namespace BOR\GamificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Action
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BOR\GamificationBundle\Entity\ActionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Action
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
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="credit", type="integer")
     */
    private $credit;

    /**
     * @var integer
     *
     * @ORM\Column(name="creditMin", type="integer")
     * @Assert\LessThanOrEqual(value="creditMax", message="Cette valeur doit être inférieure à crédit max")
     */
    private $creditMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="creditMax", type="integer")
     * @Assert\GreaterThanOrEqual(value="creditMin", message="Cette valeur doit être supérieure à crédit min")
     */
    private $creditMax;

    /**
     * @var integer
     *
     * @ORM\Column(name="experience", type="integer")
     */
    private $experience;

    /**
     * @var integer
     *
     * @ORM\Column(name="experienceMin", type="integer")
     */
    private $experienceMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="experienceMax", type="integer")
     * @Assert\GreaterThanOrEqual(value="experienceMin", message="Cette valeur doit être supérieure à expérience min")
     */
    private $experienceMax;

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
     * Set name
     *
     * @param string $name
     *
     * @return Action
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Action
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
     * Set credit
     *
     * @param integer $credit
     *
     * @return Action
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
     * Set creditMin
     *
     * @param integer $creditMin
     *
     * @return Action
     */
    public function setCreditMin($creditMin)
    {
        $this->creditMin = $creditMin;

        return $this;
    }

    /**
     * Get creditMin
     *
     * @return integer 
     */
    public function getCreditMin()
    {
        return $this->creditMin;
    }

    /**
     * Set creditMax
     *
     * @param integer $creditMax
     *
     * @return Action
     */
    public function setCreditMax($creditMax)
    {
        $this->creditMax = $creditMax;

        return $this;
    }

    /**
     * Get creditMax
     *
     * @return integer 
     */
    public function getCreditMax()
    {
        return $this->creditMax;
    }

    /**
     * Set experience
     *
     * @param integer $experience
     *
     * @return Action
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
     * Set experienceMin
     *
     * @param integer $experienceMin
     *
     * @return Action
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
     * Set experienceMax
     *
     * @param integer $experienceMax
     *
     * @return Action
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
     * @return Action
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
     * @return Action
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
}
