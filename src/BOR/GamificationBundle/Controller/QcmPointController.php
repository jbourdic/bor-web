<?php

namespace BOR\GamificationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BOR\GamificationBundle\Entity\QcmPoint;
use BOR\GamificationBundle\Form\QcmPointType;

/**
 * QcmPoint controller.
 *
 * @Route("/admin/qcmpoint")
 */
class QcmPointController extends Controller
{

    /**
     * Lists all QcmPoint entities.
     *
     * @return array
     *
     * @Route("/", name="bor_qcmpoint_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BORGamificationBundle:QcmPoint')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new QcmPoint entity.
     * @param Request $request
     * 
     * @return array
     *
     * @Route("/", name="bor_qcmpoint_create")
     * @Method("POST")
     * @Template("BORGamificationBundle:QcmPoint:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new QcmPoint();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bor_qcmpoint_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a QcmPoint entity.
     *
     * @param QcmPoint $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(QcmPoint $entity)
    {
        $form = $this->createForm(new QcmPointType(), $entity, array(
            'action' => $this->generateUrl('bor_qcmpoint_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new QcmPoint entity.
     *
     * @return array
     *
     * @Route("/new", name="bor_qcmpoint_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new QcmPoint();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a QcmPoint entity.
     * @param mixed $id The entity id
     *
     * @Route("/{id}", name="bor_qcmpoint_show")
     * @return array
     *
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORGamificationBundle:QcmPoint')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find QcmPoint entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing QcmPoint entity.
     * @param mixed $id The entity id
     *
     * @Route("/{id}/edit", name="bor_qcmpoint_edit")
     * @return array
     *
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORGamificationBundle:QcmPoint')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find QcmPoint entity.');
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
    * Creates a form to edit a QcmPoint entity.
    *
    * @param QcmPoint $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(QcmPoint $entity)
    {
        $form = $this->createForm(new QcmPointType(), $entity, array(
            'action' => $this->generateUrl('bor_qcmpoint_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing QcmPoint entity.
     * @param Request $request
     * @param mixed   $id The entity id
     *
     * @Route("/{id}", name="bor_qcmpoint_update")
     * @return array
     *
     * @Method("PUT")
     * @Template("BORGamificationBundle:QcmPoint:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORGamificationBundle:QcmPoint')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find QcmPoint entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('bor_qcmpoint_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a QcmPoint entity.
     * @param Request $request
     * @param mixed   $id The entity id
     *
     * @return RedirectResponse
     *
     * @Route("/{id}", name="bor_qcmpoint_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BORGamificationBundle:QcmPoint')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find QcmPoint entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('bor_qcmpoint_index'));
    }

    /**
     * Creates a form to delete a QcmPoint entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bor_qcmpoint_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
