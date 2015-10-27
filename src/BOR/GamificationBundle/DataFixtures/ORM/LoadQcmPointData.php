<?php

namespace BOR\GamificationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BOR\GamificationBundle\Entity\QcmPoint;

/**
 * Class LoadLevelData
 *
 * @package BOR\GamificationBundle\DataFixtures\ORM
 */
class LoadQcmPointData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $qcmPoint = new QcmPoint();
        $qcmPoint->setExperience(15);
        $qcmPoint->setQcmPoints(1);

        $manager->persist($qcmPoint);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3; // l'ordre dans lequel les fichiers sont chargés
    }
}
