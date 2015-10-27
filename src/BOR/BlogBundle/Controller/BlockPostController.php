<?php

namespace BOR\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BOR\BlogBundle\Entity\BlockPost;
use BOR\BlogBundle\Form\BlockPostType;

/**
 * BlockPost controller.
 *
 */
class BlockPostController extends Controller
{

    /**
     * Lists all BlockPost entities.
     *
     * @return array
     *
     * @Route("/admin/blockpost", name="bor_blockpost_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BORBlogBundle:BlockPost')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new BlockPost entity.
     * @param Request $request
     *
     * @return array
     *
     * @Route("/blockpost/create", name="blockpost_create")
     * @Method("POST")
     * @Template("BORBlogBundle:BlockPost:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new BlockPost();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $id = $this->getRequest()->get('id');
            $em = $this->getDoctrine()->getManager();
            $post = $em->getRepository('BORBlogBundle:Post')->find($id);
            $entity->setPost($post);

            $user = $this->container->get('security.context')->getToken()->getUser();
            $entity->setUser($user);
            $entity->setCreatedOn();

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bor_post_show', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a BlockPost entity.
     *
     * @param BlockPost $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(BlockPost $entity)
    {
        $id =  $this->getRequest()->get('id');
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('BORBlogBundle:Post')->find($id);
        $entity->setPost($post);

        $form = $this->createForm(new BlockPostType(), $entity, array(
            'action' => $this->generateUrl('bor_blockpost_create', array('id'=>$id)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new BlockPost entity.
     *
     * @return array
     *
     * @Route("/blockpost/create", name="bor_blockpost_create")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new BlockPost();

        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a BlockPost entity.
     * @param int $id
     *
     * @return array
     *
     * @Route("/admin/blockpost/{id}", name="bor_blockpost_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORBlogBundle:BlockPost')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BlockPost entity.');
        }
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing BlockPost entity.
     * @param int $id
     *
     * @return array
     *
     * @Route("/admin/blockpost/{id}/edit", name="bor_blockpost_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORBlogBundle:BlockPost')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BlockPost entity.');
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
    * Creates a form to edit a BlockPost entity.
    *
    * @param BlockPost $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(BlockPost $entity)
    {
        $form = $this->createForm(new BlockPostType(), $entity, array(
            'action' => $this->generateUrl('bor_blockpost_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing BlockPost entity.
     * @param Request $request
     * @param int     $id
     *
     * @return array
     *
     * @Route("/admin/blockpost/{id}", name="bor_blockpost_update")
     * @Method("PUT")
     * @Template("BORBlogBundle:BlockPost:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORBlogBundle:BlockPost')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BlockPost entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setUpdatedOn();
            $em->flush();

            return $this->redirect($this->generateUrl('bor_blockpost_edit', array('id' => $id)));
        }
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a BlockPost entity.
     * @param Request $request
     * @param int     $id
     *
     * @return RedirectResponse
     *
     * @Route("/admin/blockpost/{id}", name="bor_blockpost_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BORBlogBundle:BlockPost')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find BlockPost entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('bor_blockpost_list'));
    }

    /**
     * Creates a form to delete a BlockAdvert entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bor_blockpost_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
