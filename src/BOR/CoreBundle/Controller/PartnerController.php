<?php

namespace BOR\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BOR\CoreBundle\Entity\Partner;
use BOR\CoreBundle\Form\PartnerType;

/**
 * Partner controller.
 *
 */
class PartnerController extends Controller
{

    /**
     * Lists all Partner entities.
     *
     * @return array
     *
     * @Route("/partenaire", name="bor_partenaire_index")
     * @Route("/admin/partenaire", name="bor_admin_partenaire_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $defaultTemplate = 'BORCoreBundle:Partner:index.html.twig';
        if ($currentRoute = $this->get('request')->get('_route') == "bor_admin_partenaire_index" && $this->get('security.context')->isGranted('ROLE_ADMIN') ) {
            $defaultTemplate = 'BORCoreBundle:Admin:Partner/index.html.twig';
        }

        $entities = $em->getRepository('BORCoreBundle:Partner')->findAll();

        return $this->render(
            $defaultTemplate,
            array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Partner entity.
     * @param Request $request
     *
     * @return array
     *
     * @Route("/admin/partenaire/create", name="bor_admin_partenaire_create")
     * @Method("POST")
     * @Template("BORCoreBundle:Partner:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Partner();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $date = new \Datetime();
            $em = $this->getDoctrine()->getManager();
            $entity->setCreatedOn($date);
            $entity->setUpdatedOn($date);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bor_admin_partenaire_index', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Partner entity.
     *
     * @param Partner $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Partner $entity)
    {
        $form = $this->createForm(new PartnerType(), $entity, array(
            'action' => $this->generateUrl('bor_admin_partenaire_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Partner entity.
     *
     * @return array
     *
     * @Route("/admin/partenaire/new", name="bor_admin_partenaire_new")
     * @Method("GET")
     * @Template("BORCoreBundle:Admin:Partner/new.html.twig")
     */
    public function newAction()
    {
        $entity = new Partner();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Partner entity.
     * @param mixed $id The entity id
     *
     * @return array
     *
     * @Route("/partenaire/{id}", name="bor_partenaire_show")
     * @Route("/admin/partenaire/{id}", name="bor_admin_partenaire_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $defaultTemplate = 'BORCoreBundle:Partner:show.html.twig';
        if ($currentRoute = $this->get('request')->get('_route') == "bor_admin_partenaire_show" && $this->get('security.context')->isGranted('ROLE_ADMIN') ) {
            $defaultTemplate = 'BORCoreBundle:Admin:Partner/show.html.twig';
        }

        $entity = $em->getRepository('BORCoreBundle:Partner')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Partner entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render(
            $defaultTemplate, array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Partner entity.
     * @param mixed $id The entity id
     *
     * @return array
     *
     * @Route("/admin/partenaire/{id}/edit", name="bor_admin_partenaire_edit")
     * @Method("GET")
     * @Template("BORCoreBundle:Admin:Partner/edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $defaultTemplate = 'BORCoreBundle:Admin:Partner/edit.html.twig';

        $entity = $em->getRepository('BORCoreBundle:Partner')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Partner entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render(
            $defaultTemplate, array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Partner entity.
    *
    * @param Partner $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Partner $entity)
    {
        $form = $this->createForm(new PartnerType(), $entity, array(
            'action' => $this->generateUrl('bor_admin_partenaire_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Partner entity.
     * @param Request $request
     * @param mixed   $id The entity id
     *
     * @return array
     *
     * @Route("/admin/partenaire/{id}", name="bor_admin_partenaire_update")
     * @Method("PUT")
     * @Template("BORCoreBundle:Partner:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORCoreBundle:Partner')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Partner entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('bor_admin_partenaire_update', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Partner entity.
     * @param Request $request
     * @param mixed   $id The entity id
     *
     * @return RedirectResponse
     *
     * @Route("/admin/partenaire/{id}", name="bor_admin_partenaire_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BORCoreBundle:Partner')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Partner entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('bor_admin_partenaire_index'));
    }

    /**
     * Creates a form to delete a Partner entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bor_admin_partenaire_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
