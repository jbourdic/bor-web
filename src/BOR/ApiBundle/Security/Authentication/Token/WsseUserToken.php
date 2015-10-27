<?php

namespace BOR\ApiBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * Class WsseUserToken
 *
 * @package BOR\ApiBundle\Security\Authentication\Token
 */
class WsseUserToken extends AbstractToken
{
    public $created;
    public $digest;
    public $nonce;

    /**
     * @param array $roles
     */
    public function __construct(array $roles = array())
    {
        parent::__construct($roles);

        // If the user has roles, consider it authenticated
        $this->setAuthenticated(count($roles) > 0);
    }

    /**
     * @return string
     */
    public function getCredentials()
    {
        return '';
    }
}
