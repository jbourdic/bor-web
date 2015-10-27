<?php

namespace BOR\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use BOR\UserBundle\Entity\User;

/**
 * Class LoadUserData
 *
 * @package BOR\UserBundle\DataFixtures\ORM
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    /**
     * {@inheritDoc}
     */

    public function load(ObjectManager $manager)
    {

        $user = new User();
        $user->setUsername('admin@blabla.business');
        $user->setEmail('admin@blabla.business');
        $user->setPlainPassword('admin');
        $user->setEnabled(true);
        $user->setRoles(
            array(
                 'ROLE_ADMIN'
            )
        );
        $user->setCivility('Monsieur');
        $user->setFirstname('admin');
        $user->setLastname('admin');
        $user->setPhone('000000000');
        $user->setType('admin');
        $user->setZipCode('69000');
        $user->setActive(1);

        $manager->persist($user);
        $manager->flush();
        $this->addReference('admin', $user);

        $user = new User();
        $user->setUsername('admin@admin.com');
        $user->setEmail('admin@admin.com');
        $user->setPlainPassword('admin');
        $user->setEnabled(true);
        $user->setRoles(
            array(
                 'ROLE_ADMIN'
            )
        );
        $user->setCivility('Monsieur');
        $user->setFirstname('admin');
        $user->setLastname('admin');
        $user->setPhone('000000000');
        $user->setType('admin');
        $user->setZipCode('69000');
        $user->setActive(1);

        $manager->persist($user);
        $manager->flush();
        $this->addReference('admin2', $user);

        $user2 = new User();
        $user2->setUsername('user@user.com');
        $user2->setEmail('user@user.com');
        $user2->setPlainPassword('user');
        $user2->setEnabled(true);
        $user2->setRoles(
            array(
            )
        );
        $user2->setCivility('Monsieur');
        $user2->setFirstname('user');
        $user2->setLastname('user');
        $user2->setPhone('000000000');
        $user2->setType('user');
        $user2->setZipCode('69000');
        $user2->setActive(1);

        $manager->persist($user2);
        $manager->flush();
        $this->addReference('user', $user2);

        $user3 = new User();
        $user3->setUsername('expert@expert.com');
        $user3->setEmail('expert@expert.com');
        $user3->setPlainPassword('expert');
        $user3->setEnabled(true);
        $user3->setRoles(
            array(
                'ROLE_EXPERT'
            )
        );
        $user3->setCivility('Monsieur');
        $user3->setFirstname('expert');
        $user3->setLastname('expert');
        $user3->setPhone('000000000');
        $user3->setType('expert');
        $user3->setZipCode('69000');
        $user3->setActive(1);

        $manager->persist($user3);
        $manager->flush();

        $this->addReference('expert', $user3);




    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // l'ordre dans lequel les fichiers sont chargés
    }
}
