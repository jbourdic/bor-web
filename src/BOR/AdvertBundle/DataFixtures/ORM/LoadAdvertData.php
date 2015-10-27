<?php

namespace BOR\AdvertBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BOR\AdvertBundle\Entity\Advert;

/**
 * Class LoadAdvertData
 *
 * @package BOR\AdvertBundle\DataFixtures\ORM
 */
class LoadAdvertData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $advert = new Advert();
        $advert->setTitle('Appartement Lyon 6');
        $advert->setUser($this->getReference('user'));
        $advert->setDescription('Appartement Lyon 6 bien placé');
        $advert->setLodgmentType('Appartement');
        $advert->setTransactType('Location');
        $advert->setCharges(0);
        $advert->setPrice(450);
        $advert->setTax(0);
        $advert->setStreet('Rue Sully');
        $advert->setStreetNumber(5);
        $advert->setZipCode('69006');
        $advert->setCity('Lyon');
        $advert->setCountry('France');
        $advert->setLat(45.770548);
        $advert->setLng(4.841854);
        $advert->setActive(1);

        $manager->persist($advert);

        $advert2 = new Advert();
        $advert2->setTitle('Maison Montpellier');
        $advert2->setUser($this->getReference('user'));
        $advert2->setDescription('Maison Montpellier bien placé');
        $advert2->setLodgmentType('Maison');
        $advert2->setTransactType('Vente');
        $advert2->setCharges(0);
        $advert2->setPrice(1450000);
        $advert2->setTax(0);
        $advert2->setStreet('Place de la comedie');
        $advert2->setStreetNumber(1);
        $advert2->setZipCode('34000');
        $advert2->setCity('Montpellier');
        $advert2->setCountry('France');
        $advert2->setLat(43.6083216);
        $advert2->setLng(3.8786518);
        $advert2->setActive(1);

        $manager->persist($advert2);
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
