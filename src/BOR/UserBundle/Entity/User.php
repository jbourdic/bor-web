<?php

namespace BOR\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use Doctrine\Common\DataFixtures\ReferenceRepository;

/**
 * Class User
 *
 * @package BOR\UserBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="User")
 * @ORM\HasLifecycleCallbacks()
 *
 * @ExclusionPolicy("all")
 */
class User extends BaseUser
{

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    protected $id;

    /**
     * @var string $civility
     *
     * @ORM\Column(name="civility", type="string", length=10)
     * @Expose
     */
    protected $civility;

    /**
     * @var string $firstname
     *
     * @ORM\Column(name="firstname", type="string", length=50)
     * @Expose
     */
    protected $firstname;

    /**
     * @var string $lastname
     *
     * @ORM\Column(name="lastname", type="string", length=50)
     * @Expose
     */
    protected $lastname;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=250, nullable=true)
     * @Expose
     */
    protected $description = "";

    /**
     * @var string $job
     *
     * @ORM\Column(name="job", type="string", length=250, nullable=true)
     * @Expose
     */
    protected $job = "";

    /**
     * @var string $phone
     *
     * @ORM\Column(name="phone", type="string", length=30)
     */
    protected $phone;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=50)
     * @Expose
     */
    protected $type;

    /**
     * @var string $type
     *
     * @ORM\Column(name="zipCode", type="string", length=10, nullable=true)
     */
    protected $zipCode;

    /**
     * @var string $active
     *
     * @ORM\Column(name="active", type="boolean")
     * @Expose
     */
    protected $active = true;

    /**
     * @var string $subscribeEnd
     *
     * @ORM\Column( type="date")
     * @Expose
     */
    protected $subscribeEnd;

    /**
     * @var string $presentationVideo
     *
     * @ORM\Column( type="string", nullable=true, length=250)
     * @Expose
     */
    protected $presentationVideo;

    /**
     * @var string $website
     *
     * @ORM\Column( type="string", nullable=true, length=250)
     * @Expose
     */
    protected $website;

    /**
     * @ORM\OneToOne(targetEntity="BOR\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(nullable=true)
     * @Expose
     */
    protected $media;

    /**
     * @ORM\ManyToOne(targetEntity="BOR\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    protected $sponsor;

    /**
     * @var string $tokenMooc
     *
     * @ORM\Column(name="tokenMooc", type="string", length=40, nullable=true)
     */
    protected $tokenMooc;

    /**
     * @var string $hasMoocAccount
     *
     * @ORM\Column(name="hasMoocAccount", type="boolean", nullable=true)
     */
    protected $hasMoocAccount = false;

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
     * @var string $braintreeId
     *
     * @ORM\Column(name="braintree_id", type="string", nullable=true)
     */
    protected $braintreeId;

    /**
     * @var string $company
     *
     * @ORM\Column(name="company", type="string", length=255, nullable=true)
     * @Expose
     */
    protected $company;

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
     * Set civility
     *
     * @param string $civility
     *
     * @return User
     */
    public function setCivility($civility)
    {
        $this->civility = $civility;

        return $this;
    }

    /**
     * Get civility
     *
     * @return string
     */
    public function getCivility()
    {
        return $this->civility;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return User
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set zipCode
     *
     * @param string $type
     *
     * @return User
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
     * Set active
     *
     * @param boolean $active
     *
     * @return User
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
     * Set media
     *
     * @param Media $media
     *
     * @return User
     */
    public function setMedia($media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return User
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
     * @return User
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
     * @ORM\PrePersist
     */
    public function setCompanyValue()
    {
        if ($this->company == null) {
            $this->company = $this->firstname." ".$this->lastname;
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedOn = new \DateTime();
    }


    /**
     * Set sponsor
     *
     * @param \BOR\UserBundle\Entity\User $sponsor
     *
     * @return User
     */
    public function setSponsor(\BOR\UserBundle\Entity\User $sponsor = null)
    {
        $this->sponsor = $sponsor;

        return $this;
    }

    /**
     * Get sponsor
     *
     * @return \BOR\UserBundle\Entity\User
     */
    public function getSponsor()
    {
        return $this->sponsor;
    }

    /**
     * Set subscribeEnd
     *
     * @param \DateTime $subscribeEnd
     *
     * @return User
     */
    public function setSubscribeEnd($subscribeEnd)
    {
        $this->subscribeEnd = $subscribeEnd;

        return $this;
    }

    /**
     * Get subscribeEnd
     *
     * @return \DateTime
     */
    public function getSubscribeEnd()
    {
        return $this->subscribeEnd;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        parent::setEmail($email);
        $this->setUsername($email);
    }


    /**
     * @ORM\PrePersist
     *
     * @return \User
     */
    public function setSubscribeEndValue()
    {
        $this->subscribeEnd = new \DateTime('yesterday');

        return $this;
    }

    /**
     * Get tokenMooc
     *
     * @return string
     */
    public function getTokenMooc()
    {
        return $this->tokenMooc;
    }

    /**
     * Set tokenMooc
     *
     * @param string $tokenMooc
     */
    public function setTokenMooc($tokenMooc)
    {
        $this->tokenMooc = $tokenMooc;
    }

    /**
     * @ORM\PrePersist
     */
    public function generateTokenMooc()
    {
        $this->tokenMooc = sha1(uniqid() . $this->salt);
    }

    /**
     * Set company
     *
     * @param string $company
     *
     * @return User
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set braintreeId
     *
     * @param string $braintreeId
     *
     * @return User
     */
    public function setBraintreeId($braintreeId)
    {
        $this->braintreeId = $braintreeId;

        return $this;
    }

    /**
     * Get braintreeId
     *
     * @return string
     */
    public function getBraintreeId()
    {
        return $this->braintreeId;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return User
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
     * Set job
     *
     * @param string $job
     *
     * @return User
     */
    public function setJob($job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job
     *
     * @return string
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set presentationVideo
     *
     * @param string $presentationVideo
     * @return User
     */
    public function setPresentationVideo($presentationVideo)
    {
        $this->presentationVideo = $presentationVideo;

        return $this;
    }

    /**
     * Get presentationVideo
     *
     * @return string
     */
    public function getPresentationVideo()
    {
        return $this->presentationVideo;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return User
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set hasMoocAccount
     *
     * @param boolean $hasMoocAccount
     * @return User
     */
    public function setHasMoocAccount($hasMoocAccount)
    {
        $this->hasMoocAccount = $hasMoocAccount;

        return $this;
    }

    /**
     * Get hasMoocAccount
     *
     * @return boolean
     */
    public function getHasMoocAccount()
    {
        return $this->hasMoocAccount;
    }
}
