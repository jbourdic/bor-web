<?php

namespace BOR\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BOR\UserBundle\Entity\BlockUser;
use BOR\UserBundle\Form\BlockUserType;
use BOR\UserBundle\Entity\User;
/**
 * BlockUser controller.
 *
 */
class BlockUserController extends Controller
{

    /**
     * Lists all BlockUser entities.
     *
     * @return array
     * @Route("/admin/blockuser", name="bor_blockuser")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UserBundle:BlockUser')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new BlockUser entity.
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @Route("/blockuser/create", name="bor_blockuser_create")
     * @Method("POST")
     * @Template("UserBundle:BlockUser:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new BlockUser();
        $idUser = $request->get('idUser');
        $form = $this->createCreateForm($entity, $idUser);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUserBlocked($em->getRepository('UserBundle:User')->find($idUser));
            if ($this->get('security.context')->isGranted('ROLE_USER')) {
                $entity->setUser($this->container->get('security.context')->getToken()->getUser());
            }else{
                return $this->redirect($this->generateUrl('fos_user_security_login'));
            }
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bor_user_expert'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a BlockUser entity.
     *
     * @param BlockUser $entity The entity
     * @param int       $idUser
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(BlockUser $entity, $idUser)
    {
        $form = $this->createForm(new BlockUserType(), $entity, array(
            'action' => $this->generateUrl('bor_blockuser_create', array('idUser'=> $idUser)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new BlockUser entity.
     * @param int $idUser
     *
     * @return array
     * @Route("/blockuser/new", name="bor_blockuser_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $entity = new BlockUser();
        $idUser = $request->get('idUser');
        if (isset($idUser) && $idUser > 0) {
            $form   = $this->createCreateForm($entity, $idUser);
        } else {
            throw $this->createNotFoundException('Unable to find the user ID.');
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a BlockUser entity.
     * @param mixed $id The entity id
     *
     * @return array
     *
     * @Route("/admin/blockuser/{id}", name="bor_blockuser_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:BlockUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BlockUser entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing BlockUser entity.
     * @param mixed $id The entity id
     *
     * @return array
     *
     * @Route("/admin/blockuser/{id}/edit", name="bor_blockuser_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:BlockUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BlockUser entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a BlockUser entity.
    *
    * @param BlockUser $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(BlockUser $entity)
    {
        $form = $this->createForm(new BlockUserType(), $entity, array(
            'action' => $this->generateUrl('bor_blockuser_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing BlockUser entity.
     * @param Request $request
     * @param mixed   $id The entity id
     *
     * @return array
     *
     * @Route("/admin/blockuser/{id}", name="bor_blockuser_update")
     * @Method("PUT")
     * @Template("UserBundle:BlockUser:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:BlockUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BlockUser entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('bor_blockuser_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a BlockUser entity.
     * @param Request $request
     * @param mixed   $id The entity id
     *
     * @return RedirectResponse
     *
     * @Route("/admin/blockuser/{id}", name="bor_blockuser_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UserBundle:BlockUser')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find BlockUser entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('bor_blockuser'));
    }

    /**
     * Creates a form to delete a BlockUser entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bor_blockuser_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
