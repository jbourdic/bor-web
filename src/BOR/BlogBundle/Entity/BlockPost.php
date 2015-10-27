<?php

namespace BOR\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BlockPost
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BOR\BlogBundle\Entity\BlockPostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class BlockPost
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
     * @ORM\ManyToOne(targetEntity="BOR\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="BOR\BlogBundle\Entity\Post")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $post;

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
     * Set reason
     *
     * @param string $reason
     *
     * @return BlockPost
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
     * Get createdOn
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
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
     * Set user
     *
     * @param \BOR\UserBundle\Entity\User $user
     *
     * @return BlockPost
     */
    public function setUser(\BOR\UserBundle\Entity\User $user = null)
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
     * Set post
     *
     * @param \BOR\BlogBundle\Entity\Post $post
     *
     * @return BlockPost
     */
    public function setPost(\BOR\BlogBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \BOR\BlogBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedOn()
    {
        $this->createdOn = new \DateTime();
        $this->updatedOn = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedOn()
    {
        $this->updatedOn = new \DateTime();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->id;
    }
}
