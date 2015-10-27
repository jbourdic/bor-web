<?php

namespace BOR\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BOR\BlogBundle\Entity\Post;
use BOR\MediaBundle\Entity\Media;
use BOR\BlogBundle\Form\PostType;
use BOR\BlogBundle\Form\SearchType;
use BOR\BlogBundle\Form\PostAdminType;

/**
 * Post controller.
 */
class PostController extends Controller
{
    /**
     * Lists all Post entities.
     *
     * @return array
     *
     * @Route("/article/", name="bor_post_list")
     * @Route("/admin/article/", name="bor_admin_post_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

        $defaultTemplate = 'BORBlogBundle:Post:index.html.twig';
        if ($currentRoute = $this->get('request')->get('_route') == "bor_admin_post_list" && $this->get('security.context')->isGranted('ROLE_ADMIN') ) {
            $defaultTemplate = 'BORBlogBundle:Admin:Post/index.html.twig';
        }

        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository('BORBlogBundle:Post');

        $searchForm = $this->createSearchForm();
        $params = $this->getRequest()->get("bor_blogbundle_post");

        $keywords = "";
        if (isset($params)) {
            $keywords = $params['search'];
            $entities = $repo->searchFind($keywords);
        } else {
            $entities = $em->getRepository('BORBlogBundle:Post')->findBy(array( 'active' => '1' ));
            if ($currentRoute = $this->get('request')->get('_route') == "bor_admin_post_list") {
                $entities = $repo->findAll();
            }
        }

        return $this->render(
            $defaultTemplate,
            array(
                'entities'      => $entities,
                'searchForm'    => $searchForm->createView(),
                'keywords'      => $keywords,
            )
        );
    }


    /**
     * Lists all Post entities.
     *
     * @return array
     *
     * @Route("/article/news", name="bor_post_news")
     * @Route("/admin/article/news", name="bor_admin_post_news")
     * @Method("GET")
     * @Template()
     */
    public function showNewsAction()
    {
        $defaultTemplate = 'BORBlogBundle:Post:showNews.html.twig';
        if ($currentRoute = $this->get('request')->get('_route') == "bor_admin_post_news" && $this->get('security.context')->isGranted('ROLE_ADMIN') ) {
            $defaultTemplate = 'BORBlogBundle:Admin:Post/showNews.html.twig';
        }

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BORBlogBundle:Post')->findBy(array('type' => 'news', 'active' => '1'));

        return $this->render(
            $defaultTemplate,
            array(
                'entities' => $entities,
            )
        );
    }

    /**
     * Lists all Post entities.
     *
     * @return array
     *
     * @Route("/article/renover", name="bor_post_renovate")
     * @Route("/admin/article/renovate", name="bor_admin_post_renovate")
     * @Method("GET")
     * @Template()
     */
    public function showRenovateAction()
    {
        $defaultTemplate = 'BORBlogBundle:Post:showRenovate.html.twig';
        if ($currentRoute = $this->get('request')->get('_route') == "bor_admin_post_news" && $this->get('security.context')->isGranted('ROLE_ADMIN') ) {
            $defaultTemplate = 'BORBlogBundle:Admin:Post/showRenovate.html.twig';
        }

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BORBlogBundle:Post')->findBy(array('type' => 'renovate', 'active' => '1'));

        return $this->render(
            $defaultTemplate,
            array(
                'entities' => $entities,
            )
        );
    }

    /**
     * Creates a new Post entity.
     * @param Request $request
     *
     * @return array
     *
     * @Route("/article/create", name="bor_post_create_post")
     * @Route("/admin/article/create", name="bor_admin_post_create_post")
     * @Method("POST")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $media = new Media();
        $entity = new Post();
        $entity->setMedia($media);
        $form = $this->createCreateForm($entity);

        $defaultTemplate = 'BORBlogBundle:Post:new.html.twig';
        if ($currentRoute = $this->get('request')->get('_route') == "bor_admin_post_create_post" && $this->get('security.context')->isGranted('ROLE_ADMIN') ) {
            $defaultTemplate = 'BORBlogBundle:Admin:Post/new.html.twig';
            $form = $this->createCreateAdminForm($entity);
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUser($this->getUser());

            if (!$form->get('metaTitle')->getData()) {
                $entity->setMetaTitle($form->get('title')->getData());
            }
            if (!$form->get('metaDescription')->getData()) {
                $desc = substr(strip_tags($form->get('content')->getData()), 0, 200);
                $entity->setMetaDescription($desc);
            }

            if (isset($request->files->get('bor_blogbundle_post')['media']['file'])) {
                $media->upload();
                $em->persist($media);
            } else {
                $entity->setMedia(null);
            }
            $em->persist($entity);
            $em->flush();

            if ($form->getName() == "bor_admin_blogbundle_post" && $this->get('security.context')->isGranted('ROLE_ADMIN')) {
                return $this->redirect($this->generateUrl('bor_admin_post_list', array('id' => $entity->getId())));
            } else {
                return $this->redirect($this->generateUrl('bor_post_list', array('id' => $entity->getId())));
            }

        }

        return $this->render(
            $defaultTemplate,
            array(
                'entity' => $entity,
                'form'   => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Post entity.
     * @param Post $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Post $entity)
    {
        $form = $this->createForm(new PostType(), $entity, array(
            'action' => $this->generateUrl('bor_post_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Creates a form to create a Post entity.
     *
     * @param Post $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateAdminForm(Post $entity)
    {
        $form = $this->createForm(new PostAdminType(), $entity, array(
            'action' => $this->generateUrl('bor_admin_post_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Post entity.
     *
     * @return array
     *
     * @Route("/article/create", name="bor_post_create")
     * @Route("/admin/article/create", name="bor_admin_post_create")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Post();
        $form   = $this->createCreateForm($entity);

        $defaultTemplate = 'BORBlogBundle:Post:new.html.twig';
        if ($currentRoute = $this->get('request')->get('_route') == "bor_admin_post_create" && $this->get('security.context')->isGranted('ROLE_ADMIN') ) {
            $defaultTemplate = 'BORBlogBundle:Admin:Post/new.html.twig';
            $form = $this->createCreateAdminForm($entity);
        }

        return $this->render(
            $defaultTemplate,
            array(
                'entity' => $entity,
                'form'   => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Post entity.
     * @param int $id
     *
     * @return array
     *
     * @Route("/article/{id}", name="bor_post_show")
     * @Route("/admin/article/{id}", name="bor_admin_post_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $defaultTemplate = 'BORBlogBundle:Post:show.html.twig';
        if ($currentRoute = $this->get('request')->get('_route') == "bor_admin_post_show" && $this->get('security.context')->isGranted('ROLE_ADMIN') ) {
            $defaultTemplate = 'BORBlogBundle:Admin:Post/show.html.twig';
        }

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORBlogBundle:Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $url = $this->generateUrl('bor_post_show', array('id' => $entity->getId()));


        return $this->render(
            $defaultTemplate,
            array(
                'entity'    => $entity,
                'url'       => $url,
            )
        );
    }

    /**
     * Displays a form to edit an existing Post entity.
     * @param int $id
     *
     * @return array
     *
     * @Route("/article/edit/{id}", name="bor_post_edit")
     * @Route("/admin/article/edit/{id}", name="bor_admin_post_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {


        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORBlogBundle:Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $editForm = $this->createEditForm($entity);

        $defaultTemplate = 'BORBlogBundle:Post:edit.html.twig';
        if ($currentRoute = $this->get('request')->get('_route') == "bor_admin_post_edit" && $this->get('security.context')->isGranted('ROLE_ADMIN') ) {
            $defaultTemplate = 'BORBlogBundle:Admin:Post/edit.html.twig';
            $editForm = $this->createAdminEditForm($entity);
        }

        return $this->render(
            $defaultTemplate,
            array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
            )
        );
    }

    /**
    * Creates a form to edit a Post entity.
    *
    * @param Post $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Post $entity)
    {
        $form = $this->createForm(new PostType(), $entity, array(
        'action' => $this->generateUrl('bor_post_update', array('id' => $entity->getId())),
        'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
    * Creates a form to edit a Post entity.
    *
    * @param Post $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createAdminEditForm(Post $entity)
    {
        $form = $this->createForm(new PostAdminType(), $entity, array(
            'action' => $this->generateUrl('bor_admin_post_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Post entity.
     * @param Request $request
     * @param int     $id
     *
     * @return array
     *
     * @Route("/article/{id}", name="bor_post_update")
     * @Route("/admin/article/{id}", name="bor_admin_post_update")
     * @Method("PUT")
     * @Template()
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORBlogBundle:Post')->find($id);

        $oldMedia = $entity->getMedia();

        $media = new Media();
        $entity->setMedia($media);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $editForm = $this->createEditForm($entity);
        $defaultTemplate = 'BORBlogBundle:Post:edit.html.twig';
        if ($currentRoute = $this->get('request')->get('_route') == "bor_admin_post_update" && $this->get('security.context')->isGranted('ROLE_ADMIN') ) {
            $defaultTemplate = 'BORBlogBundle:Admin:Post/edit.html.twig';
            $editForm = $this->createAdminEditForm($entity);
        }

        $editForm->handleRequest($request);

        $params = $this->getRequest()->get('bor_blogbundle_post');

        if ($editForm->isValid()) {
            if (isset($params['active'])) {
                if ($params['active'] == true) {
                    $entity->setActive(true);
                } else {
                    $entity->setActive(false);
                }
            }
            $entity->setUpdatedOn();
            if (isset($request->files->get('bor_blogbundle_post')['media']['file'])) {
                // si la photo a été changée, on upload le nouveau media
                $media->upload();
                $em->persist($media);
                // une fois l'upload effectué on supprime l'ancien média
                $em->remove($oldMedia);
            } else {
                $entity->setMedia($oldMedia);
            }

            $em->persist($entity);
            $em->flush();


            if ($editForm->getName() == "bor_admin_blogbundle_post" && $this->get('security.context')->isGranted('ROLE_ADMIN')) {
                return $this->redirect($this->generateUrl('bor_admin_post_show', array('id' => $id)));
            } else {
                return $this->redirect($this->generateUrl('bor_post_show', array('id' => $id)));
            }
        }

        return $this->render(
            $defaultTemplate,
            array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to search a Post entity with keywords.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createSearchForm()
    {
        $form = $this->createForm(new SearchType());

        $form->add('submit', 'submit', array('label' => 'Search'));

        return $form;
    }

    /**
     * Lists all Post entities.
     *
     * @return array
     *
     * @Route("/mon-profil/mes-articles/", name="bor_post_me")
     * @Method("GET")
     * @Template("BORBlogBundle:Post:myPost.html.twig")
     */
    public function myPostAction()
    {

        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository('BORBlogBundle:Post');

        $searchForm = $this->createSearchForm();
        $params = $this->getRequest()->get("bor_blogbundle_post");

        $keywords = "";
        if (isset($params)) {
            $keywords = $params['search'];
            $entities = $repo->searchFind($keywords);
        } else {
            $entities = $em->getRepository('BORBlogBundle:Post')->findBy(array( 'active' => '1' ));
            if ($currentRoute = $this->get('request')->get('_route') == "bor_post_me") {
                $entities = $repo->findByUser($this->getUser());
            }
        }

        return array(
            'entities'      => $entities,
            'searchForm'    => $searchForm->createView(),
            'keywords'      => $keywords,
        );
    }
}
