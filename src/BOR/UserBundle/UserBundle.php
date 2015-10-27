<?php

namespace BOR\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class UserBundle
 *
 * @package BOR\UserBundle
 */
class UserBundle extends Bundle
{
    /**
     * @return string
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}