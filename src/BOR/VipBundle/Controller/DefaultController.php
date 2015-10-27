<?php

namespace BOR\VipBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class DefaultController
 *
 * @package BOR\VipBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * Index action
     *
     * @Route("/vip/")
     * @Template()
     */
    public function indexAction()
    {
        return ;
    }
}
