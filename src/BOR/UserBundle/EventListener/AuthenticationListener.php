<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BOR\UserBundle\EventListener;

use BOR\GamificationBundle\Entity\Statistics;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Security\LoginManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;

/**
 * Class AuthenticationListener
 *
 * @package BOR\UserBundle\EventListener
 *
 */
class AuthenticationListener implements EventSubscriberInterface
{
    private $loginManager;
    private $firewallName;
    private $em;
    private $statistics;

    /**
     * @param LoginManagerInterface $loginManager
     * @param string                $firewallName
     * @param EntityManager         $em
     */
    public function __construct(LoginManagerInterface $loginManager, $firewallName, $em)
    {
        $this->loginManager = $loginManager;
        $this->firewallName = $firewallName;
        $this->em = $em;
        $this->statistics = new Statistics();
    }

    /**
     * @return array
     *
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => 'authenticate',
            FOSUserEvents::REGISTRATION_CONFIRMED => 'authenticate',
            FOSUserEvents::RESETTING_RESET_COMPLETED => 'authenticate',
        );
    }

    /**
     * @param FilterUserResponseEvent $event
     */
    public function authenticate(FilterUserResponseEvent $event)
    {
        if (!$event->getUser()->isEnabled()) {
            return;
        }

        try {
            //Création d'une statistique lors de la création
            $this->statistics->setUser($event->getUser());
            $this->em->persist($this->statistics);
            $this->em->flush();

            $this->loginManager->loginUser($this->firewallName, $event->getUser(), $event->getResponse());

            $event->getDispatcher()->dispatch(FOSUserEvents::SECURITY_IMPLICIT_LOGIN, new UserEvent($event->getUser(), $event->getRequest()));
        } catch (AccountStatusException $ex) {
            // We simply do not authenticate users which do not pass the user
            // checker (not enabled, expired, etc.).
        }
    }
}
