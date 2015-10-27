<?php

namespace BOR\AdvertBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BOR\AdvertBundle\Entity\Advert;
use BOR\MediaBundle\Entity\Media;
use BOR\AdvertBundle\Form\AdvertType;
use BOR\AdvertBundle\Form\FilterType;
use BOR\AdvertBundle\Form\AdvertAdminType;
use BOR\AdvertBundle\Form\SearchType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\QueryBuilder;

/**
 * Advert controller.
 */
class AdvertController extends Controller
{
    /**
     * Lists all Advert entities.
     *
     * @return array
     *
     * @Route("/annonce/", name="bor_advert_list")
     * @Route("/admin/annonce/", name="bor_admin_advert_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $defaultTemplate = 'BORAdvertBundle:Advert:index.html.twig';
        if ($currentRoute = $this->get('request')->get('_route') == "bor_admin_advert_list" && $this->get('security.context')->isGranted('ROLE_ADMIN') ) {
            $defaultTemplate = 'BORAdvertBundle:Admin:Advert/index.html.twig';
        }

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('BORAdvertBundle:Advert');
        $favoriteRepo = $em->getRepository('BORAdvertBundle:Favorite');
        $favorites = $favoriteRepo->findByUser($this->getUser());
        // paramètres renvoyés par le(s) formulaire(s)
        $params = $this->getRequest()->get("bor_advertbundle_advert");

        // formulaire de recherche
        $searchForm = $this->createSearchForm();
        $keywords = '';

        // formulaire de filtre
        $form = $this->createFilterForm();

        // pour le formulaire de filtre
        if (isset($params)) {
            $qb = $repo->filterInit();
            $entities = $repo->filterFind($qb, $params);

            // pour le formulaire de recherche
            if (isset($params['search'])) {
                $keywords = $params['search'];
                $entities = $repo->searchFind($keywords);
            }
        } else {
            $entities = $repo->findAll();
        }

        return $this->render(
            $defaultTemplate,
            array(
                'entities' => $entities,
                'searchForm' => $searchForm->createView(),
                'keywords' => $keywords,
                'favorites' => $favorites,
                'form_filter' => $form->createView(),
            )
        );
    }

    /**
     * Creates a new Advert entity.
     * @param Request $request
     *
     * @return array
     *
     * @Route("/annonce/create", name="bor_advert_create_post")
     * @Route("/admin/annonce/create", name="bor_admin_advert_create_post")
     * @Method("POST")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $entity = new Advert();
        $form = $this->createCreateForm($entity);

        $defaultTemplate = 'BORAdvertBundle:Advert:new.html.twig';
        if ($currentRoute = $this->get('request')->get('_route') == "bor_admin_advert_create_post" && $this->get('security.context')->isGranted('ROLE_ADMIN') ) {
            $defaultTemplate = 'BORAdvertBundle:Admin:Advert/new.html.twig';
            $form = $this->createCreateAdminForm($entity);
        }

        $form->handleRequest($request);

        $entity->setLat('0');
        $entity->setLng('0');

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $pars = $this->calcGeocoding($entity);
            if ($pars['lat'] != 0 && $pars['lng'] != 0) {
                $entity->setLat($pars['lat']);
                $entity->setLng($pars['lng']);
            } else {
                $errorMessage = "Veuillez entrer une adresse valide";
                $form->get('street')->addError(new FormError($errorMessage));
                $form->get('streetNumber')->addError(new FormError($errorMessage));
                $form->get('zipCode')->addError(new FormError($errorMessage));
                $form->get('city')->addError(new FormError($errorMessage));
                $form->get('country')->addError(new FormError($errorMessage));

                return $this->render($defaultTemplate, array(
                    'form' => $form->createView(),
                ));
            }
            if ($currentRoute = $this->get('request')->get('_route') == "bor_advert_create_post") {
                $entity->setActive(true);
            }

            $medias = array();
            for ($i=0; $i < 5; $i++) {
                if (isset($request->files->get('bor_advertbundle_advert')['upload'.$i])) {
                    // si il y a une photo à upload
                    $medias[$i] = new Media();
                    $medias[$i]->file = $request->files->get('bor_advertbundle_advert')['upload'.$i];
                    $medias[$i]->setUser($this->getUser());
                    $medias[$i]->upload();
                    $entity->addMedia($medias[$i]);
                } else if ($imageIdFromGallery = $form->get('gallery'.$i)->getData()) {
                    $medias[$i] = new Media();
                    $medias[$i] = $em->getRepository('BORMediaBundle:Media')->find($imageIdFromGallery);
                    $entity->addMedia($medias[$i]);
                }
            }
            $entity->setUser($this->getUser());

            $em->persist($entity);
            $em->flush();
            if ($form->getName() == "bor_admin_advertbundle_advert" && $this->get('security.context')->isGranted('ROLE_ADMIN')) {
                return $this->redirect($this->generateUrl('bor_admin_advert_list', array('id' => $entity->getId())));
            } else {
                return $this->redirect($this->generateUrl('bor_advert_list', array('id' => $entity->getId())));
            }
        }

        return $this->render(
            $defaultTemplate,
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Advert entity.
     *
     * @param Advert $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Advert $entity)
    {
        $galleryChoices = array('options' => $this->getGalleryChoices());
        $form = $this->createForm(new AdvertType($galleryChoices), $entity, array(
            'action' => $this->generateUrl('bor_advert_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Creates a form to create a Advert entity.
     *
     * @param Advert $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateAdminForm(Advert $entity)
    {
        $galleryChoices = array('options' => $this->getGalleryChoices());
        $form = $this->createForm(new AdvertAdminType($galleryChoices), $entity, array(
            'action' => $this->generateUrl('bor_admin_advert_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Get Gallery Choices
     * array fed to AdvertType to create the choice input of the gallery
     *
     * @return array
     */
    private function getGalleryChoices()
    {
        $em = $this->getDoctrine()->getManager();
        $gallery = $em->getRepository('BORMediaBundle:Media')->findByUser($this->getUser());

        return array_map(function($galleryEl) {
            return array($galleryEl->getId() => $galleryEl->getWebPath());
        }, $gallery);
    }

    /**
     * Get Gallery Selected
     * array fed to AdvertType to create the selected options of the gallery
     *
     * @return array
     */
    private function getGallerySelected($advert)
    {
        return array_map(function($media) {
            return $media->getId();
        }, $advert->getMedias()->toArray());
    }

    /**
     * Displays a form to create a new Advert entity.
     *
     * @return array
     *
     * @Route("/annonce/create", name="bor_advert_create")
     * @Route("/admin/annonce/create", name="bor_admin_advert_create")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {

        $entity = new Advert();
        $form = $this->createCreateForm($entity);


        $defaultTemplate = 'BORAdvertBundle:Advert:new.html.twig';
        if ($currentRoute = $this->get('request')->get('_route') == "bor_admin_advert_create" && $this->get('security.context')->isGranted('ROLE_ADMIN') ) {
            $defaultTemplate = 'BORAdvertBundle:Admin:Advert/new.html.twig';
            $form = $this->createCreateAdminForm($entity);
        }

        return $this->render(
            $defaultTemplate,
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }


    /**
     * Displays a form to edit an existing Advert entity.
     * @param int $id
     *
     * @return array
     *
     * @Route("/annonce/{id}", name="bor_advert_edit")
     * @Route("/admin/annonce/{id}", name="bor_admin_advert_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORAdvertBundle:Advert')->find($id);

        if (isset($entity) && $this->getUser() != $entity->getUser() && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('bor_advert_get', array('id' => $entity->getId(), 'slug' => $entity->getTitle())));
        }
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Advert entity.');
        }

        $editForm = $this->createEditForm($entity);
        $defaultTemplate = 'BORAdvertBundle:Advert:edit.html.twig';
        if ($currentRoute = $this->get('request')->get('_route') == "bor_admin_advert_edit" && $this->get('security.context')->isGranted('ROLE_ADMIN') ) {
            $defaultTemplate = 'BORAdvertBundle:Admin:Advert/edit.html.twig';
            $editForm = $this->createEditAdminForm($entity);
        }

        return $this->render(
            $defaultTemplate,
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }


    /**
     * Creates a form to edit a Advert entity.
     *
     * @param Advert $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Advert $entity)
    {
        $galleryChoices = array('options' => $this->getGalleryChoices(), 'data' => $this->getGallerySelected($entity));
        $form = $this->createForm(new AdvertType($galleryChoices), $entity, array(
            'action' => $this->generateUrl('bor_advert_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Creates a form to edit a Advert entity.
     *
     * @param Advert $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditAdminForm(Advert $entity)
    {
        $galleryChoices = array('options' => $this->getGalleryChoices(), 'data' => $this->getGallerySelected($entity));
        $form = $this->createForm(new AdvertAdminType($galleryChoices), $entity, array(
            'action' => $this->generateUrl('bor_admin_advert_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Advert entity.
     * @param Request $request
     * @param int     $id
     *
     * @return array
     *
     * @Route("/annonce/{id}", name="bor_advert_update")
     * @Route("/admin/annonce/{id}", name="bor_admin_advert_update")
     * @Method("PUT")
     * @Template()
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BORAdvertBundle:Advert')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Advert entity.');
        }

        $editForm = $this->createEditForm($entity);

        $defaultTemplate = 'BORAdvertBundle:Advert:edit.html.twig';
        if ($currentRoute = $this->get('request')->get('_route') == "bor_admin_advert_update" && $this->get('security.context')->isGranted('ROLE_ADMIN') ) {
            $defaultTemplate = 'BORAdvertBundle:Admin:Advert/edit.html.twig';
            $editForm = $this->createEditAdminForm($entity);
        }
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $pars = $this->calcGeocoding($entity);
            if ($pars['lat'] != 0 && $pars['lng'] != 0) {
                $entity->setLat($pars['lat']);
                $entity->setLng($pars['lng']);
            } else {
                $errorMessage = "Veuillez entrer une adresse valide";
                $editForm->get('street')->addError(new FormError($errorMessage));
                $editForm->get('streetNumber')->addError(new FormError($errorMessage));
                $editForm->get('zipCode')->addError(new FormError($errorMessage));
                $editForm->get('city')->addError(new FormError($errorMessage));
                $editForm->get('country')->addError(new FormError($errorMessage));

                return $this->render($defaultTemplate, array(
                    'edit_form' => $editForm->createView(),
                ));
            }

            $entity->removeAllMedia();
            $medias = array();
            for ($i=0; $i < 5; $i++) {
                if (isset($request->files->get('bor_advertbundle_advert')['upload'.$i])) {
                    // si il y a une photo à upload
                    $medias[$i] = new Media();
                    $medias[$i]->file = $request->files->get('bor_advertbundle_advert')['upload'.$i];
                    $medias[$i]->setCreatedOn();
                    $medias[$i]->setUser($this->getUser());
                    $medias[$i]->upload();
                    $entity->addMedia($medias[$i]);
                } else if ($imageIdFromGallery = $editForm->get('gallery'.$i)->getData()) {
                    $medias[$i] = new Media();
                    $medias[$i] = $em->getRepository('BORMediaBundle:Media')->find($imageIdFromGallery);
                    $entity->addMedia($medias[$i]);
                }
            }
            $entity->setUser($this->getUser());

            $em->persist($entity);
            $em->flush();
            if ($editForm->getName() == "bor_admin_advertbundle_advert" && $this->get('security.context')->isGranted('ROLE_ADMIN')) {
                return $this->redirect($this->generateUrl('bor_admin_advert_list', array('id' => $entity->getId())));
            } else {
                return $this->redirect($this->generateUrl('bor_advert_update', array('id' => $id)));
            }
        }

        return $this->render(
            $defaultTemplate,
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Finds and displays a Advert entity.
     * @param int     $id
     * @param string  $slug
     *
     * @return array
     *
     * @Route("/annonce/{id}/{slug}", name="bor_advert_get")
     * @Route("/admin/annonce/{id}/{slug}", name="bor_admin_advert_get")
     * @Method("GET")
     * @Template()
     */
    public function getAction($id, $slug)
    {
        $defaultTemplate = 'BORAdvertBundle:Advert:get.html.twig';
        if ($currentRoute = $this->get('request')->get('_route') == "bor_admin_advert_get" && $this->get('security.context')->isGranted('ROLE_ADMIN') ) {
            $defaultTemplate = 'BORAdvertBundle:Admin:Advert/get.html.twig';
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORAdvertBundle:Advert')->findOneBy(array('id' => $id, 'title' => $slug));
        $favorite = $em->getRepository('BORAdvertBundle:Favorite')->findOneBy(array('advert' => $entity, 'user' => $this->getUser()));

        $isFavorite = !empty($favorite);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Advert entity.');
        }

        $url = $this->generateUrl('bor_advert_get', array('id' => $id, 'slug' => $slug));

        return $this->render(
            $defaultTemplate,
            array(
                'entity' => $entity,
                'url' => $url,
                'isFavorite' => $isFavorite,
            )
        );
    }

    private function calcGeocoding($entity)
    {
        $geocoder = "http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false";

        /* Initialize variable */
        $myStreet = $entity->getStreet();
        $myStreetNumber = $entity->getStreetNumber();
        $myZipCode = $entity->getZipCode();
        $myCity = $entity->getCity();
        $myCountry = $entity->getCountry();

        $address = $myStreetNumber;
        $address .= ' ' . $myStreet . ', ' . $myZipCode . ', ' . $myCity . ', ' . $myCountry;

        /* Request google map API & decode Json lat,lng */
        $query = sprintf($geocoder, urlencode(utf8_encode($address)));
        $result = json_decode(file_get_contents($query));

        if ($result->status == 'ZERO_RESULTS') {
            $lat = 0;
            $lng = 0;
        } else {
            $json = $result->results[0];
            $lat = (string) $json->geometry->location->lat;
            $lng = (string) $json->geometry->location->lng;
        }

        return array('lat' => $lat, 'lng' => $lng);
    }

    /**
     * Creates a form to search a Advert entity with keywords.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createSearchForm()
    {
        $form = $this->createForm(new SearchType());

        return $form;
    }

    /**
     * Creates a form to filter an Advert entity.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createFilterForm()
    {
        $form = $this->createForm(new FilterType());

        return $form;
    }

    /**
     * Lists all Advert entities.
     *
     * @return array
     *
     * @Route("/mon-profil/mes-annonces/", name="bor_advert_me")
     * @Method("GET")
     * @Template("BORAdvertBundle:Advert:myAdvert.html.twig")
     */
    public function myAdvertAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('BORAdvertBundle:Advert');
        $favoriteRepo = $em->getRepository('BORAdvertBundle:Favorite');
        $favorites = $favoriteRepo->findByUser($this->getUser());
        $entities = $repo->findByUser($this->getUser());

        return array(
            'entities' => $entities,
            'favorites' => $favorites,
        );
    }
}
