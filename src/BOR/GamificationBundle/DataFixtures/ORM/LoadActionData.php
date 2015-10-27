<?php

namespace BOR\GamificationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BOR\GamificationBundle\Entity\Action;

/**
 * Class LoadActionData
 *
 * @package BOR\GamificationBundle\DataFixtures\ORM
 */
class LoadActionData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $action = new Action();
        $action->setExperience(50);
        $action->setName("connexion");
        $action->setDescription("Points accordés à la connexion de l'utilisateur");
        $action->setExperienceMin(10);
        $action->setExperienceMax(50);
        $action->setCredit(0);
        $action->setCreditMin(0);
        $action->setCreditMax(0);

        $manager->persist($action);

        $action = new Action();
        $action->setExperience(5);
        $action->setName("clicBouton");
        $action->setDescription("Points accordés au clic sur un bouton d'action");
        $action->setExperienceMin(0);
        $action->setExperienceMax(5);
        $action->setCredit(0);
        $action->setCreditMin(0);
        $action->setCreditMax(0);

        $manager->persist($action);

        $action = new Action();
        $action->setExperience(10);
        $action->setName("achatAbonnement");
        $action->setDescription("Points accordés pour l'achat d'un abonnement");
        $action->setExperienceMin(10);
        $action->setExperienceMax(50);
        $action->setCredit(0);
        $action->setCreditMin(0);
        $action->setCreditMax(0);

        $manager->persist($action);

        $action = new Action();
        $action->setExperience(5);
        $action->setName("favorisation");
        $action->setDescription("Points accordés pour la mise en favoris d'une annonce");
        $action->setExperienceMin(0);
        $action->setExperienceMax(10);
        $action->setCredit(0);
        $action->setCreditMin(0);
        $action->setCreditMax(0);

        $manager->persist($action);

        $action = new Action();
        $action->setExperience(10);
        $action->setName("uploadPhoto");
        $action->setDescription("Points accordés pour l'upload d'une photo");
        $action->setExperienceMin(0);
        $action->setExperienceMax(10);
        $action->setCredit(0);
        $action->setCreditMin(0);
        $action->setCreditMax(0);

        $manager->persist($action);

        $action = new Action();
        $action->setExperience(0);
        $action->setName("parrainage");
        $action->setDescription("Points accordés pour le parrainage d'un membre");
        $action->setExperienceMin(0);
        $action->setExperienceMax(0);
        $action->setCredit(10);
        $action->setCreditMin(0);
        $action->setCreditMax(5);

        $manager->persist($action);

        $action = new Action();
        $action->setExperience(0);
        $action->setName("commenterCours");
        $action->setDescription("Points accordés pour la mise d'un commentaire sur un cours");
        $action->setExperienceMin(0);
        $action->setExperienceMax(0);
        $action->setCredit(0);
        $action->setCreditMin(0);
        $action->setCreditMax(0);

        $manager->persist($action);

        $action = new Action();
        $action->setExperience(0);
        $action->setName("faireQcm");
        $action->setDescription("Points accordés pour la résolution d'un QCM");
        $action->setExperienceMin(0);
        $action->setExperienceMax(0);
        $action->setCredit(0);
        $action->setCreditMin(0);
        $action->setCreditMax(0);

        $manager->persist($action);

        $action = new Action();
        $action->setExperience(0);
        $action->setName("gagnerDefi");
        $action->setDescription("Points accordés pour avoir gagné un défi");
        $action->setExperienceMin(0);
        $action->setExperienceMax(0);
        $action->setCredit(0);
        $action->setCreditMin(0);
        $action->setCreditMax(0);

        $manager->persist($action);

        $action = new Action();
        $action->setExperience(5);
        $action->setName("coursVu");
        $action->setDescription("Points accordés pour voir un cours");
        $action->setExperienceMin(0);
        $action->setExperienceMax(5);
        $action->setCredit(0);
        $action->setCreditMin(0);
        $action->setCreditMax(0);

        $manager->persist($action);

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
