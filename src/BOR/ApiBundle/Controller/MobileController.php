<?php

namespace BOR\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\RequestParam;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;

use BOR\UserBundle\Entity\User;
use BOR\AdvertBundle\Entity\Advert;
use BOR\AdvertBundle\Form\AdvertType;
use BOR\MediaBundle\Entity\Media;
use BOR\MediaBundle\Form\MediaType;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * Class MobileController
 *
 * @Cache(maxage="0")
 */
class MobileController extends FOSRestController
{
    /**
     * @param string $email
     *
     * @return User
     *
     * @Get("/open/user/{email}")
     * @View()
     *
     * @ApiDoc(
     *  section="Open",
     *  description="Get l'utilisateur en fonction de l'email",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when the user is not found"
     *  }
     * )
     */
    public function getUserAction($email)
    {
        $em = $this->getDoctrine();
        $user = $em->getRepository('UserBundle:User')->findOneByEmail($email);
        if (!is_object($user)) {
            throw $this->createNotFoundException();
        }

        return $user;
    }

    /**
     * @return User
     *
     * @View()
     *
     * @ApiDoc(
     *  section="Authenticated",
     *  description="Get l'utilisateur courant",
     *  parameters={
     *      {"name"="X-WSSE", "dataType"="headers", "required"=true, "description"="Générateur headers WSSE : http://www.teria.com/~koseki/tools/wssegen/ | Hashage du pass : http://openclassrooms.com/forum/sujet/symfony2-fosuserbundle-84608#message-7171831 "}
     *  },
     *  statusCodes={
     *      200="Returned when successful",
     *      403="Returned when WSSE headers failed to authenticate the user"
     *  }
     * )
     */
    public function getMeAction()
    {
        $this->forwardIfNotAuthenticated();

        return $this->getUser();
    }


    /**
     * @param Request $request
     *
     * @return User
     *
     * @Post("/open/register")
     * @View()
     *
     * @RequestParam(name="fos_user_registration_form[civility]", requirements="Monsieur|Madame", description="")
     * @RequestParam(name="fos_user_registration_form[firstname]", requirements=".+", description="")
     * @RequestParam(name="fos_user_registration_form[lastname]", requirements=".+", description="")
     * @RequestParam(name="fos_user_registration_form[email]", requirements=".+@.+", description="")
     * @RequestParam(name="fos_user_registration_form[plainPassword][first]", requirements=".+", description="")
     * @RequestParam(name="fos_user_registration_form[plainPassword][second]", requirements=".+", description="")
     * @RequestParam(name="fos_user_registration_form[phone]", requirements=".+", description="")
     *
     * @ApiDoc(
     *  section="Open",
     *  description="Permet d'inscrire un utilisateur",
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when user data aren't well inputed"
     *  }
     * )
     */
    public function registerAction(Request $request)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            //Attribution du rôle en fonction de la checkbox.
            if ($form->get('isExpert')->getData()) {
                $user->setType('expert');
                $user->setRoles(array('ROLE_EXPERT'));
            } else {
                $user->setType('user');
                $user->setRoles(array('ROLE_USER'));
            }
            //Si un email de parrainage est saisi alors on traite le cas
            if ($form->get('sponsorEmail')->getData()) {

                //Initialisation de doctrine
                $em = $this->getDoctrine()->getManager();
                $userRepository = $em->getRepository('UserBundle:User');

                //On vérifie que l'utilisateur existe bien.
                $sponsor = $userRepository->findOneByEmail($form->get('sponsorEmail')->getData());
                if (isset($sponsor)) {
                    $user->setSponsor($sponsor);
                } else {
                    //Si l'email du parrain est introuvable, on redirige l'utilisateur vers le formulaire en lui indiquant.
                    return array('error'=>'L\'email du parrain n\'est pas valide');
                }
            }

            //Sauvegarde de l'utilisateur
            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_registration_confirmed');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $this->getUser();

        } else {
            return array(
                'form' => $form,
            );
        }
    }


    /**
     * @return array
     *
     * @Get("/advert")
     * @View()
     *
     * @ApiDoc(
     *  section="Authenticated",
     *  parameters={
     *      {"name"="X-WSSE", "dataType"="headers", "required"=true, "description"="Générateur headers WSSE : http://www.teria.com/~koseki/tools/wssegen/ | Hashage du pass : http://openclassrooms.com/forum/sujet/symfony2-fosuserbundle-84608#message-7171831 "}
     *  },
     *  description="Renvoie les annonces"
     * )
     */
    public function getAdvertAction()
    {
        $em = $this->getDoctrine();
        $advert = $em->getRepository('BORAdvertBundle:Advert')->findAll();
        if (!is_array($advert)) {
            throw $this->createNotFoundException();
        }

        return $advert;
    }


    /**
     * Shortcut to throw a AccessDeniedException($message) if the user is not authenticated
     * @param String $message The message to display (default:'warn.user.notAuthenticated')
     */
    protected function forwardIfNotAuthenticated($message = 'warn.user.notAuthenticated')
    {
        if (!is_object($this->getUser())) {
            throw new AccessDeniedException($message);
        }
    }

    /**
     * @param int $advertId
     *
     * @return Favorite
     *
     * @Post("/favorite/{advertId}")
     * @View()
     *
     * @ApiDoc(
     *  section="Authenticated",
     *  parameters={
     *      {"name"="X-WSSE", "dataType"="headers", "required"=true, "description"="Générateur headers WSSE : http://www.teria.com/~koseki/tools/wssegen/ | Hashage du pass : http://openclassrooms.com/forum/sujet/symfony2-fosuserbundle-84608#message-7171831 "}
     *  },
     *  description="Ajoute en favoris l'annonce advertId pour l'utilisateur courant",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when advert not found",
     *      403="Returned when unauthorized"
     *  }
     * )
     */
    public function addFavoriteAction($advertId)
    {
        $user = $this->getUser();
        try {
            $favorite = $this->container->get('bor_advert.favorite')->addFavorite($user, $advertId);
        } catch (NotFoundResourceException $e) {
            throw $this->createNotFoundException($e->getMessage());
        }

        return $favorite;
    }

    /**
     * @param int $advertId
     *
     * @return Favorite
     *
     * @Delete("/favorite/{advertId}")
     * @View()
     *
     * @ApiDoc(
     *  section="Authenticated",
     *  parameters={
     *      {"name"="X-WSSE", "dataType"="headers", "required"=true, "description"="Générateur headers WSSE : http://www.teria.com/~koseki/tools/wssegen/ | Hashage du pass : http://openclassrooms.com/forum/sujet/symfony2-fosuserbundle-84608#message-7171831 "}
     *  },
     *  description="Enlève des favoris l'annonce advertId pour l'utilisateur courant",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when advert or favorite not found",
     *      403="Returned when unauthorized"
     *  }
     * )
     */
    public function removeFavoriteAction($advertId)
    {
        $user = $this->getUser();
        try {
            $favorite = $this->container->get('bor_advert.favorite')->removeFavorite($user, $advertId);
        } catch (NotFoundResourceException $e) {
            throw $this->createNotFoundException($e->getMessage());
        }

        return $favorite;
    }

    /**
     * @return array
     *
     * @Get("/favorite")
     * @View()
     *
     * @ApiDoc(
     *  section="Authenticated",
     *  parameters={
     *      {"name"="X-WSSE", "dataType"="headers", "required"=true, "description"="Générateur headers WSSE : http://www.teria.com/~koseki/tools/wssegen/ | Hashage du pass : http://openclassrooms.com/forum/sujet/symfony2-fosuserbundle-84608#message-7171831 "}
     *  },
     *  description="Renvoie les annonces favoris de l'utilisateur courant",
     *  statusCodes={
     *      200="Returned when successful",
     *      403="Returned when unauthorized"
     *  }
     * )
     */
    public function listFavoriteAction()
    {
        $user = $this->getUser();
        try {
            $favorites = $this->container->get('bor_advert.favorite')->listFavoriteByUser($user);
        } catch (NotFoundResourceException $e) {
            throw $this->createNotFoundException($e->getMessage());
        }

        return $favorites;
    }


    /**
     * @param string $keyword
     * 
     * @return array
     *
     * @Get("/advert/search/{keyword}")
     * @View()
     *
     * @ApiDoc(
     *  section="Authenticated",
     *  description="Renvoie les annonces par mot clé",
     *  statusCodes={
     *      200="Returned when successful",
     *      403="Returned when unauthorized"
     *  }
     * )
     */
    public function searchAdvertAction($keyword)
    {
        $em = $this->getDoctrine();
        $repo = $em->getRepository('BORAdvertBundle:Advert');
        try {
            $adverts = $repo->searchFind($keyword);
        } catch (NotFoundResourceException $e) {
            throw $this->createNotFoundException($e->getMessage());
        }

        return $adverts;
    }

    /**
     * @param Request $request
     *
     * @return array
     *
     * @RequestParam(name="type", requirements="", description="")
     * @RequestParam(name="localization", requirements="", description="")
     * @RequestParam(name="priceMin", requirements="", description="")
     * @RequestParam(name="priceMax", requirements="", description="")
     * @RequestParam(name="keywords", requirements="", description="")
     *
     * @Get("/advert/filter")
     * @View()
     *
     * @ApiDoc(
     *  section="Authenticated",
     *  description="Renvoi la liste des annonces selon les critères désirés",
     *  statusCodes={
     *      200="Returned when successful",
     *      400="No result found"
     *  }
     * )
     */
    public function filterAdvertAction(Request $request)
    {
        $em = $this->getDoctrine();
        $repo = $em->getRepository('BORAdvertBundle:Advert');

        $params = array();
        $advertsFiltered = array();
        $keywords = $request->get("keywords");

        $params['priceType'] = "priceTTC";
        $params['order'] = "asc";
        $params['zipCode'] = $request->get("localization");
        $params['transactType'] = $request->get("type");
        $params['min'] = $request->get("priceMin");
        $params['max'] = $request->get("priceMax");

        try {
            $qb = $repo->filterInit();
            $advertsFiltered = $repo->filterFind($qb, $params);

            if (isset($keywords) && $keywords != "") {
                $advertsSearched = $repo->searchFind($keywords);
                foreach ($advertsFiltered as $index => $advertFiltered) {
                    if (in_array($advertFiltered, $advertsSearched) == false) {
                        unset($advertsFiltered[$index]);
                    }
                }
            }
        } catch (NotFoundResourceException $e) {
            throw $this->createNotFoundException($e->getMessage());
        }

        return $advertsFiltered;
    }

    /**
     * @param int $advertId
     *
     * @return object
     *
     * @Get("/advert/isfavorite/{advertId}")
     * @View()
     *
     * @ApiDoc(
     *  section="Authenticated",
     *  parameters={
     *      {"name"="X-WSSE", "dataType"="headers", "required"=true, "description"="Générateur headers WSSE : http://www.teria.com/~koseki/tools/wssegen/ | Hashage du pass : http://openclassrooms.com/forum/sujet/symfony2-fosuserbundle-84608#message-7171831 "}
     *  },
     *  description="Renvoi si l'annonce est en favoris pour l'utilisateur ou non",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when advert or favorite not found",
     *      403="Returned when unauthorized"
     *  }
     * )
     */
    public function isFavoriteAction($advertId)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine();
        $repo = $em->getManager()->getRepository('BORAdvertBundle:Favorite');
        $result = $repo->findBy(array(
            'user' => $user,
            'advert' => $advertId
        ));

        try {

            return array('result' => !empty($result));
        } catch (NotFoundResourceException $e) {
            throw $this->createNotFoundException($e->getMessage());
        }
    }

    /**
     * @param Request $request
     *
     * @return Media
     *
     * @Post("/media")
     * @View()
     *
     * @ApiDoc(
     *  section="Authenticated",
     *  parameters={
     *      {"name"="bor_mediabundle[file]", "dataType"="file", "required"=true, "description"=""},
     *      {"name"="Content-Type", "dataType"="headers", "required"=true, "description"="multipart/form-data"},
     *      {"name"="X-WSSE", "dataType"="headers", "required"=true, "description"="Générateur headers WSSE : http://www.teria.com/~koseki/tools/wssegen/ | Hashage du pass : http://openclassrooms.com/forum/sujet/symfony2-fosuserbundle-84608#message-7171831 "}
     *  },
     *  description="Ajoute un media à la gallerie de l'utilisateur courant",
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when request malformed",
     *      403="Returned when unauthorized"
     *  }
     * )
     */
    public function addMediaAction(Request $request)
    {
        $entity = new Media();
        $form = $this->createForm(new MediaType(), $entity, array(
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $entity->setUser($this->getUser());
            $entity->setCreatedOn();
            $entity->upload();

            $em->persist($entity);
            $em->flush();

            return $entity;
        }

        return new HttpException(400, "Bad request : RTFM");
    }

    /**
     * @return array
     *
     * @Get("/media")
     * @View()
     *
     * @ApiDoc(
     *  section="Authenticated",
     *  parameters={
     *      {"name"="X-WSSE", "dataType"="headers", "required"=true, "description"="Générateur headers WSSE : http://www.teria.com/~koseki/tools/wssegen/ | Hashage du pass : http://openclassrooms.com/forum/sujet/symfony2-fosuserbundle-84608#message-7171831 "}
     *  },
     *  description="Retourne les medias de la gallerie de l'utilisateur courant",
     *  statusCodes={
     *      200="Returned when successful",
     *      403="Returned when unauthorized"
     *  }
     * )
     */
    public function getMediaAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BORMediaBundle:Media')->findByUser($this->getUser());

        return $entities;
    }

    /**
     * @param int $id
     *
     * @return Media
     *
     * @Delete("/media/{id}")
     * @View()
     *
     * @ApiDoc(
     *  section="Authenticated",
     *  parameters={
     *      {"name"="X-WSSE", "dataType"="headers", "required"=true, "description"="Générateur headers WSSE : http://www.teria.com/~koseki/tools/wssegen/ | Hashage du pass : http://openclassrooms.com/forum/sujet/symfony2-fosuserbundle-84608#message-7171831 "}
     *  },
     *  description="Supprime un media de la gallerie de l'utilisateur courant",
     *  statusCodes={
     *      200="Returned when successful",
     *      403="Returned when unauthorized"
     *  }
     * )
     */
    public function removeMediaAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BORMediaBundle:Media')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }
        if ($entity->getUser()->getId() !== $this->getUser()->getId()) {
            throw new HttpException(403, "You can't delete other user's media");
        }

        $em->remove($entity);
        $em->flush();

        return 'OK';
    }
}
