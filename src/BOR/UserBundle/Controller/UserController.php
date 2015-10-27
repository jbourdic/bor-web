<?php

namespace BOR\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BOR\UserBundle\Entity\User;
use BOR\UserBundle\Form\UserType;
use BOR\UserBundle\Form\UserAdminType;
/**
 * User controller.
 *
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     * @return array
     *
     * @Route("/admin/user", name="bor_admin_user")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $defaultTemplate = 'UserBundle:Admin:User/index.html.twig';
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UserBundle:User')->findAll();

        return $this->render(
            $defaultTemplate,
            array(
                'entities' => $entities,
            )
        );
    }

    /**
     * Disable user account.
     *
     * @return array
     *
     * @Route("/user/disable", name="bor_user_disable")
     * @Method("GET")
     */
    public function disableUserAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

            if (isset($user)) {
                $user->setEnabled(false);
                $em->persist($user);
                $em->flush();

                return $this->redirect($this->generateUrl('fos_user_security_logout'));
            }
        return $this->redirect($this->generateUrl('fos_user_security_login'));
    }

    /**
     * Lists all Expert entities.
     *
     * @return array
     *
     * @Route("/experts", name="bor_user_expert")
     * @Method("GET")
     * @Template("UserBundle:User:expert.html.twig")
     */
    public function expertAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UserBundle:User')->findBy(array('type' => 'expert'));

        return array('entities' => $entities);
    }

    /**
     * Show expert all Expert entities.
     * @param integer $id
     *
     * @return array
     *
     * @Route("/experts/{id}", name="bor_user_expert_show")
     * @Method("GET")
     * @Template("UserBundle:User:show_expert.html.twig")
     */
    public function showExpertAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $expert = $em->getRepository('UserBundle:User')->find($id);
        if (!$expert) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $posts = $em->getRepository('BORBlogBundle:Post')->findByUser($expert);

        return array(
            'expert' => $expert,
            'posts' => $posts
            );
    }

    /**
     * Lists all Expert entities.
     *
     * @return array
     *
     * @Route("/mon-profil", name="bor_user_myprofil")
     * @Method("GET")
     * @Template("UserBundle:User:myProfil.html.twig")
     */
    public function myProfileAction()
    {
        return array();
    }


    /**
     * Lists all Expert entities.
     *
     * @return array
     *
     * @Route("/adherer", name="bor_user_adherer")
     * @Method("GET")
     * @Template("UserBundle:User:adherer.html.twig")
     */
    public function adhererAction()
    {
        return array();
    }

    /**
     * Creates a new User entity.
     * @param Request $request
     *
     * @return array
     *
     * @Route("/admin/user", name="bor_admin_user_create")
     * @Method("POST")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $defaultTemplate = 'UserBundle:Admin:User/new.html.twig';
        $form = $this->createCreateAdminForm($entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bor_admin_user_create', array('id' => $entity->getId())));
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
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('bor_admin_user_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateAdminForm(User $entity)
    {
        $form = $this->createForm(new UserAdminType(), $entity, array(
            'action' => $this->generateUrl('bor_admin_user_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @return array
     *
     * @Route("/admin/user/new", name="bor_admin_user_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new User();
        $form = $this->createCreateAdminForm($entity);
        $defaultTemplate = 'UserBundle:Admin:User/new.html.twig';

        if ( !$this->get('security.context')->isGranted('ROLE_ADMIN') ) {
            return $this->redirect($this->generateUrl('fos_user_registration_register'));
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
     * Finds and displays a User entity.
     * @param mixed $id The entity id
     *
     * @return array
     *
     * @Route("/admin/user/{id}", name="bor_admin_user_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        $defaultTemplate = 'UserBundle:Admin:User/show.html.twig';
        $deleteForm = $this->createDeleteForm($id);

        return $this->render(
            $defaultTemplate,
            array(
                'entity'      => $entity,
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     * @param mixed $id The entity id
     *
     * @return array
     *
     * @Route("/admin/user/{id}/edit", name="bor_admin_user_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
            
        $defaultTemplate = 'UserBundle:Admin:User/edit.html.twig';
        $editForm = $this->createEditAdminForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render(
            $defaultTemplate,
            array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }


    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditAdminForm(User $entity)
    {
        $form = $this->createForm(new UserAdminType(), $entity, array(
            'action' => $this->generateUrl('bor_admin_user_show', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing User entity.
     * @param Request $request
     * @param mixed   $id The entity id
     *
     * @return array
     *
     * @Route("/admin/user/{id}", name="bor_admin_user_update")
     * @Method("PUT")
     * @Template()
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        $defaultTemplate = 'UserBundle:Admin:User/edit.html.twig';

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditAdminForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            switch ($editForm->get('type')->getData()) {
                case 'admin':
                    $entity->setType("admin");
                    $entity->setRoles(array('ROLE_ADMIN'));
                    break;
                case 'expert':
                    $entity->setType("expert");
                    $entity->setRoles(array('ROLE_EXPERT'));
                    break;
                case 'user':
                    $entity->setType("user");
                    $entity->setRoles(array('ROLE_USER'));
                    break;
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bor_admin_user_show', array('id' => $id)));
        }

        return $this->render(
            $defaultTemplate,
            array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }
    /**
     * Deletes a User entity.
     * @param Request $request The request
     * @param mixed   $id The entity id
     *
     * @return RedirectResponse
     *
     * @Route("/admin/user/{id}", name="bor_admin_user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UserBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('bor_admin_user'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bor_admin_user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
