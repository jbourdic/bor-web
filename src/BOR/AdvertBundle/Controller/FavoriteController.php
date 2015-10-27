<?php

namespace BOR\AdvertBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BOR\AdvertBundle\Entity\Favorite;
use BOR\AdvertBundle\Form\FavoriteType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Favorite controller.
 */
class FavoriteController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/favoris/", name="bor_favorite_add")
     * @Method("POST")
     * @Template()
     */
    public function addFavoriteAction()
    {
        $advertId = $this->getRequest()->request->get('idAdvert');
        $user = $this->getUser();
        $this->container->get("bor_advert.favorite")->addFavorite($user, $advertId);

        $this->get('bor_core.gamification')->gammificationAction($user, 'favorisation');

        return new Response('OK');

    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/favoris/delete", name="bor_favorite_remove")
     * @Method("POST")
     * @Template()
     */
    public function removeAction()
    {
        $advertId = $this->getRequest()->request->get('idAdvert');
        $user = $this->getUser();

        $this->container->get("bor_advert.favorite")->removeFavorite($user, $advertId);

        return new Response('OK');
    }

    /**
     * Lists all Favorite Advert entities.
     *
     * @return array
     *
     * @Route("/mon-profil/mes-favoris/", name="bor_favorite_me")
     * @Method("GET")
     * @Template("BORAdvertBundle:Favorite:myFavorite.html.twig")
     */
    public function myFavoriteAction()
    {
        $em = $this->getDoctrine()->getManager();
        $favoriteRepo = $em->getRepository('BORAdvertBundle:Favorite');
        $favorites = $favoriteRepo->findByUser($this->getUser());
        $adverts = array();

        foreach ($favorites as $favorite) {
            $adverts[] = $favorite->getAdvert();
        }

        return array(
            'entities' => $adverts,
            'favorites' => $favorites,
        );
    }
}
