<?php

namespace BOR\GamificationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BOR\GamificationBundle\Entity\Level;

/**
 * Class LoadLevelData
 *
 * @package BOR\GamificationBundle\DataFixtures\ORM
 */
class LoadLevelData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $level = new Level();
        $level->setExperienceMin(0);
        $level->setLevel(1);
        $level->setExperienceMax(100);
        $level->setExperienceRequired(100);
        $level->setCreditReward(50);

        $manager->persist($level);

        $level = new Level();
        $level->setExperienceMin(101);
        $level->setLevel(2);
        $level->setExperienceMax(350);
        $level->setExperienceRequired(249);
        $level->setCreditReward(50);

        $manager->persist($level);

        $level = new Level();
        $level->setExperienceMin(351);
        $level->setLevel(3);
        $level->setExperienceMax(650);
        $level->setExperienceRequired(299);
        $level->setCreditReward(100);

        $manager->persist($level);

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
