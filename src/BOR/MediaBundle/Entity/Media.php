<?php

namespace BOR\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use BOR\UserBundle\Entity\User as User;

/**
 * Media
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BOR\MediaBundle\Entity\MediaRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @ExclusionPolicy("all")
 */
class Media
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;

    /**
     * @ORM\ManyToOne(targetEntity="BOR\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     * @Expose
     */
    private $type='image';

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active=true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdOn", type="datetime")
     * @Expose
     */
    private $createdOn;


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
     * @return Media
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
     * Set path
     *
     * @param string $path
     *
     * @return Media
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Media
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Media
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
     * Set active
     *
     * @param boolean $active
     *
     * @return Media
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
     * @return Media
     *
     * @ORM\PrePersist
     */
    public function setCreatedOn()
    {
        $this->createdOn = new \DateTime();

        return $this;
    }

    /**
     * Set createdOn
     *
     * @return Media
     *
     * @ORM\PrePersist
     */
    public function setCreatedOnValue()
    {
        $this->createdOn = new \DateTime();
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
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    /**
     * @return null|string
     * @VirtualProperty
     */
    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    /**
     * @return string
     */
    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    /**
     * @return string
     */
    protected function getUploadDir()
    {
        return 'uploads';
    }

    /**
     * Upload
     *
     * @return boolean
     */
    public function upload()
    {
        if (null === $this->file) {
            return false;
        }

        $fileName = uniqid().$this->file->getClientOriginalName();
        $this->file->move($this->getUploadRootDir(), $fileName);
        $this->name = $fileName;
        $this->path = $fileName;
        $this->file = null;

        return true;
    }
}
