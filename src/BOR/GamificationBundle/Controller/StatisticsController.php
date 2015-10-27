<?php

namespace BOR\GamificationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use BOR\GamificationBundle\Entity\Statistics;
use BOR\GamificationBundle\Form\StatisticsType;
use BOR\UserBundle\Entity\User;

/**
 * Statistics controller.
 *
 * @Route("/statistiques")
 */
class StatisticsController extends Controller
{
    /**
     * Lists all Statistics entities (users with rank by Total Experience Point).
     * @param integer $page
     *
     * @return array
     *
     * @Route("/classement/{page}", name="bor_gamification_rank", defaults={"page": 1})
     * @Method("GET")
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BORGamificationBundle:Statistics')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Statistics entity.
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="statistics_create")
     * @Method("POST")
     * @Template("BORGamificationBundle:Statistics:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Statistics();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('statistics_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Statistics entity.
     *
     * @param Statistics $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Statistics $entity)
    {
        $form = $this->createForm(new StatisticsType(), $entity, array(
            'action' => $this->generateUrl('statistics_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Statistics entity.
     *
     * @return array
     *
     * @Route("/new", name="statistics_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Statistics();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Statistics entity.
     * @param mixed $id The entity id
     *
     * @return array
     *
     * @Route("/{id}", name="statistics_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORGamificationBundle:Statistics')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Statistics entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Statistics entity.
     * @param mixed $id The entity id
     *
     * @return array
     *
     * @Route("/{id}/edit", name="statistics_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORGamificationBundle:Statistics')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Statistics entity.');
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
    * Creates a form to edit a Statistics entity.
    *
    * @param Statistics $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Statistics $entity)
    {
        $form = $this->createForm(new StatisticsType(), $entity, array(
            'action' => $this->generateUrl('statistics_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Statistics entity.
     * @param Request $request
     * @param mixed   $id The entity id
     *
     * @return array
     *
     * @Route("/{id}", name="statistics_update")
     * @Method("PUT")
     * @Template("BORGamificationBundle:Statistics:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORGamificationBundle:Statistics')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Statistics entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('statistics_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Statistics entity.
     * @param Request $request
     * @param mixed   $id The entity id
     *
     * @return RedirectResponse
     *
     * @Route("/{id}", name="statistics_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BORGamificationBundle:Statistics')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Statistics entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('statistics'));
    }

    /**
     * Creates a form to delete a Statistics entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('statistics_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
