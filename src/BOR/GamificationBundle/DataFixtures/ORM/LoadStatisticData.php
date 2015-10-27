<?php

namespace BOR\GamificationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BOR\GamificationBundle\Entity\Statistics;

/**
 * Class LoadStatisticsData
 *
 * @package BOR\GamificationBundle\DataFixtures\ORM
 */
class LoadStatisticsData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $stat = new Statistics();
        $stat->setUser($this->getReference('admin'));
        $manager->persist($stat);
        $manager->flush();

        $stat2 = new Statistics();
        $stat2->setUser($this->getReference('expert'));
        $manager->persist($stat2);
        $manager->flush();

        $stat3 = new Statistics();
        $stat3->setUser($this->getReference('user'));
        $manager->persist($stat3);
        $manager->flush();

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 5; // l'ordre dans lequel les fichiers sont chargés
    }
}
