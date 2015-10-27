<?php

namespace BOR\VipBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use BOR\VipBundle\Entity\Package;

/**
 * Class LoadPackageData
 *
 * @package BOR\VipBundle\DataFixtures\ORM
 */
class LoadPackageData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $package = new Package();
        $package->setDescription("Cette offre est pour ceux qui ne veulent pas torp s'engager sur le long terme avec nous. Mais vous disposez cependant de toutes les fonctionnalités du site.");
        $package->setName("PREMIUM / MOIS");
        $package->setUniqueName("mois");
        $package->setActive(1);
        $package->setPrice(8);
        $package->setTax(1.6);
        $package->setDays(30);
        $manager->persist($package);

        $package = new Package();
        $package->setDescription("Cette offre est pour ceux qui ont envie de d'investir dans l'immobilier et dans le cours. L'accès est illimité pendant un an ! ");
        $package->setName("PREMIUM / AN");
        $package->setUniqueName("an");
        $package->setActive(1);
        $package->setPrice(96);
        $package->setTax(19.2);
        $package->setDays(365);
        $manager->persist($package);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4; // l'ordre dans lequel les fichiers sont chargés
    }
}
