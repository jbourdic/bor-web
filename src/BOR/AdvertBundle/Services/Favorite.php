<?php

namespace BOR\AdvertBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use BOR\AdvertBundle\Entity\Favorite as FavEntity;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * Class Favorite
 *
 * @package BOR\AdvertBundle\Services
 */
class Favorite
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param EntityManager $em
     * @param Request       $request
     */
    public function __construct(EntityManager $em, Request $request)
    {
        $this->em = $em;
        $this->request = $request;
    }

    /**
     * Ajouter des favoris via le service ci-dessous
     * @param User $user
     * @param int  $advertId
     *
     * @return Favorite
     */
    public function addFavorite($user, $advertId)
    {
        //Récupération de l'utilisateur selon son id
        if (!$user) {
            throw new NotFoundResourceException('Unable to find User entity.');
        }

        //Récupération de l'advert selon son id
        $advert = $this->em->getRepository('BORAdvertBundle:Advert')->find($advertId);
        if (!$advert) {
            throw new NotFoundResourceException('Unable to find Advert entity.');
        }

        //Création et persistance du favoris
        $favorite = new FavEntity();

        $favorite->setAdvert($advert);
        $favorite->setUser($user);
        $this->em->persist($favorite);
        $this->em->flush();

        return $favorite;
    }

    /**
     * Ajouter des favoris via le service ci-dessous
     * @param User $user
     * @param int  $advertId
     *
     * @return array
     */
    public function removeFavorite($user, $advertId)
    {
        //Récupération de l'utilisateur selon son id
        if (!$user) {
            throw new NotFoundResourceException('Unable to find User entity.');
        }

        //Récupération de l'advert selon son id
        $advert = $this->em->getRepository('BORAdvertBundle:Advert')->find($advertId);
        if (!$advert) {
            throw new NotFoundResourceException('Unable to find Advert entity.');
        }

        $favorite = $this->em->getRepository('BORAdvertBundle:Favorite')->findOneBy(array(
            'user' => $user,
            'advert' => $advert
        ));

        if (!$favorite) {
            throw new NotFoundResourceException('Unable to find Favorite entity.');
        }

        $this->em->remove($favorite);
        $this->em->flush();

        return $this->listFavoriteByUser($user);
    }

    /**
     * Ajouter des favoris via le service ci-dessous
     * @param User $user
     *
     * @return array
     */
    public function listFavoriteByUser($user)
    {
        //Récupération de l'utilisateur selon son id
        if (!$user) {
            throw new NotFoundResourceException('Unable to find User entity.');
        }

        $favorites = $this->em->getRepository('BORAdvertBundle:Favorite')->findBy(array(
            'user' => $user
        ));

        if (!$favorites) {
            return array();
        }

        return $favorites;
    }
}
