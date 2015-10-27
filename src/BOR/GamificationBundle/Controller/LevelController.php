<?php

namespace BOR\GamificationBundle\Controller;

use BOR\GamificationBundle\Entity\Statistics;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BOR\GamificationBundle\Entity\Level;
use BOR\GamificationBundle\Form\LevelType;

/**
 * Level controller.
 *
 * @Route("/admin/level")
 */
class LevelController extends Controller
{

    /**
     * Lists all Level entities.
     *
     * @return array
     *
     * @Route("/", name="bor_level_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BORGamificationBundle:Level')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Level entity.
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="bor_level_create")
     * @Method("POST")
     * @Template("BORGamificationBundle:Level:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Level();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $lm = $this->get("bor_gamification.level_manager");
            $lm->saveLevel($entity);

            return $this->redirect($this->generateUrl('bor_level_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Level entity.
     *
     * @param Level $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Level $entity)
    {
        $form = $this->createForm(new LevelType(), $entity, array(
            'action' => $this->generateUrl('bor_level_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Level entity.
     *
     * @return array
     *
     * @Route("/new", name="bor_level_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Level();
        //Initialisation des champs avec le niveau précédent pour la création
        $lm = $this->get("bor_gamification.level_manager");
        $entity->setLevel($lm->getNextLevelNumber());
        $entity->setExperienceMin($lm->getPreviousLevel($entity)->getExperienceMax() +1);

        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Level entity.
     * @param mixed $id The entity id
     *
     * @return array
     *
     * @Route("/{id}", name="bor_level_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORGamificationBundle:Level')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Level entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Level entity.
     * @param mixed $id The entity id
     *
     * @return array
     *
     * @Route("/{id}/edit", name="bor_level_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORGamificationBundle:Level')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Level entity.');
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
    * Creates a form to edit a Level entity.
    *
    * @param Level $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Level $entity)
    {
        $form = $this->createForm(new LevelType(), $entity, array(
            'action' => $this->generateUrl('bor_level_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Level entity.
     * @param Request $request
     * @param mixed   $id The entity id
     *
     * @return array
     *
     * @Route("/{id}", name="bor_level_update")
     * @Method("PUT")
     * @Template("BORGamificationBundle:Level:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORGamificationBundle:Level')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Level entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $lm = $this->get("bor_gamification.level_manager");
            $lm->saveLevel($entity);

            return $this->redirect($this->generateUrl('bor_level_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Level entity.
     * @param Request $request
     * @param mixed   $id The entity id
     *
     * @return RedirectResponse
     *
     * @Route("/{id}", name="bor_level_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BORGamificationBundle:Level')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Level entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('bor_level_index'));
    }

    /**
     * Creates a form to delete a Level entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bor_level_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
