<?php
/**
 * Created by PhpStorm.
 * User: jbourdic
 * Date: 24/04/2015
 * Time: 15:46
 */

namespace BOR\UserBundle\EventListener;

use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccountStatusException;

/**
 * Class LoginListener
 * @package BOR\UserBundle\EventListener
 */
class LoginListener {

    protected $userManager;

    /**
     * @param UserManagerInterface $userManager
     * @param Container $container
     */
    public function __construct(UserManagerInterface $userManager, Container $container){
        $this->userManager = $userManager;
        $this->container = $container;
    }

    /**
     * @param InteractiveLoginEvent $event
     * @return Response
     */
    public function onSecurityInteractiveLogin( InteractiveLoginEvent $event )
    {
        $user = $event->getAuthenticationToken()->getUser();
        try {
            $this->container->get('bor_core.gamification')->gammificationAction($user, 'connexion');
        } catch (AccountStatusException $ex) {

        }
        return new Response();
    }
}