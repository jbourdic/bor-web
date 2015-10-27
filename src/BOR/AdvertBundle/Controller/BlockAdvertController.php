<?php

namespace BOR\AdvertBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BOR\AdvertBundle\Entity\BlockAdvert;
use BOR\AdvertBundle\Form\BlockAdvertType;

/**
 * BlockAdvert controller.
 *
 */
class BlockAdvertController extends Controller
{

    /**
     * Lists all BlockAdvert entities.
     *
     * @return array
     *
     * @Route("/admin/blockadvert", name="bor_blockadvert_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BORAdvertBundle:BlockAdvert')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new BlockAdvert entity.
     * @param Request $request
     *
     * @return array
     *
     * @Route("/blockadvert", name="bor_blockadvert_create")
     * @Method("POST")
     * @Template("BORAdvertBundle:BlockAdvert:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new BlockAdvert();
        $idAdvert = $request->query->get('idAdvert');
        $form = $this->createCreateForm($entity, $idAdvert);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setIdAdvert($idAdvert);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bor_advert_list'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a BlockAdvert entity.
     *
     * @param BlockAdvert $entity The entity
     * @param int         $idAdvert
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(BlockAdvert $entity, $idAdvert)
    {
        $form = $this->createForm(new BlockAdvertType(), $entity, array(
            'action' => $this->generateUrl('bor_blockadvert_create', array('idAdvert'=> $idAdvert)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new BlockAdvert entity.
     * @param int $idAdvert
     *
     * @return array
     *
     * @Route("/blockadvert/new/{idAdvert}", name="bor_blockadvert_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($idAdvert)
    {
        $entity = new BlockAdvert();
        $form = $this->createCreateForm($entity, $idAdvert);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a BlockAdvert entity.
     * @param int $id
     *
     * @return array
     *
     * @Route("/admin/blockadvert/{id}", name="bor_blockadvert_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORAdvertBundle:BlockAdvert')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BlockAdvert entity.');
        }
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing BlockAdvert entity.
     * @param int $id
     *
     * @return array
     *
     * @Route("/admin/blockadvert/{id}/edit", name="bor_blockadvert_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORAdvertBundle:BlockAdvert')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BlockAdvert entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a BlockAdvert entity.
    *
    * @param BlockAdvert $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(BlockAdvert $entity)
    {
        $form = $this->createForm(new BlockAdvertType(), $entity, array(
            'action' => $this->generateUrl('bor_blockadvert_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing BlockAdvert entity.
     * @param Request $request
     * @param int     $id
     *
     * @return array
     *
     * @Route("/blockadvert/{id}", name="bor_blockadvert_update")
     * @Method("PUT")
     * @Template("BORAdvertBundle:BlockAdvert:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORAdvertBundle:BlockAdvert')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BlockAdvert entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bor_blockadvert_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a BlockAdvert entity.
     * @param Request $request
     * @param int     $id
     *
     * @return RedirectResponse
     *
     * @Route("/blockadvert/{id}", name="bor_blockadvert_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BORAdvertBundle:BlockAdvert')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find BlockAdvert entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('bor_blockadvert_index'));
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
            ->setAction($this->generateUrl('bor_blockadvert_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
