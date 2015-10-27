<?php

namespace BOR\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BOR\UserBundle\Entity\Sponsorship;
use BOR\UserBundle\Form\SponsorshipType;

/**
 * Sponsorship controller.
 *
 * @Route("/sponsorship")
 */
class SponsorshipController extends Controller
{

    /**
     * Lists all Sponsorship entities.
     *
     * @return array
     *
     * @Route("/", name="sponsorship")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UserBundle:Sponsorship')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Sponsorship entity.
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="sponsorship_create")
     * @Method("POST")
     * @Template("UserBundle:Sponsorship:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Sponsorship();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('bor_core.gamification')->gammificationAction($this->getUser(), 'parrainage');

            return $this->redirect($this->generateUrl('sponsorship_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Sponsorship entity.
     *
     * @param Sponsorship $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Sponsorship $entity)
    {
        $form = $this->createForm(new SponsorshipType(), $entity, array(
            'action' => $this->generateUrl('sponsorship_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Sponsorship entity.
     *
     * @return array
     *
     * @Route("/new", name="sponsorship_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Sponsorship();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Sponsorship entity.
     * @param mixed $id The entity id
     *
     * @return array
     *
     * @Route("/{id}", name="sponsorship_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Sponsorship')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sponsorship entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Sponsorship entity.
     * @param mixed $id The entity id
     *
     * @return array
     *
     * @Route("/{id}/edit", name="sponsorship_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Sponsorship')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sponsorship entity.');
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
    * Creates a form to edit a Sponsorship entity.
    *
    * @param Sponsorship $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Sponsorship $entity)
    {
        $form = $this->createForm(new SponsorshipType(), $entity, array(
            'action' => $this->generateUrl('sponsorship_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Sponsorship entity.
     * @param Request $request
     * @param mixed   $id The entity id
     *
     * @return array
     *
     * @Route("/{id}", name="sponsorship_update")
     * @Method("PUT")
     * @Template("UserBundle:Sponsorship:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Sponsorship')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sponsorship entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sponsorship_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Sponsorship entity.
     * @param Request $request
     * @param mixed   $id The entity id
     *
     * @return RedirectResponse
     *
     * @Route("/{id}", name="sponsorship_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UserBundle:Sponsorship')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Sponsorship entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sponsorship'));
    }

    /**
     * Creates a form to delete a Sponsorship entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sponsorship_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
