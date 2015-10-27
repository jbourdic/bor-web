<?php

namespace BOR\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BlockUser
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BOR\UserBundle\Entity\BlockUserRepository")
 */
class BlockUser
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
     * @ORM\Column(name="reason", type="string", length=550)
     */
    private $reason;

    /**
     * @ORM\ManyToOne(targetEntity="BOR\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="BOR\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $userBlocked;


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
     * @return BlockUser
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
     * Set user
     *
     * @param \BOR\UserBundle\Entity\User $user
     *
     * @return BlockUser
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
     * Set userBlocked
     *
     * @param \BOR\UserBundle\Entity\User $userBlocked
     *
     * @return BlockUser
     */
    public function setUserBlocked(\BOR\UserBundle\Entity\User $userBlocked = null)
    {
        $this->userBlocked = $userBlocked;

        return $this;
    }

    /**
     * Get userBlocked
     *
     * @return \BOR\UserBundle\Entity\User
     */
    public function getUserBlocked()
    {
        return $this->userBlocked;
    }
}
