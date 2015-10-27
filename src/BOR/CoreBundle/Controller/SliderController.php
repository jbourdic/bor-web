<?php

namespace BOR\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BOR\CoreBundle\Entity\Slider;
use BOR\MediaBundle\Entity\Media;
use BOR\CoreBundle\Entity\SliderRepository;
use BOR\CoreBundle\Form\SliderType;
use BOR\CoreBundle\Form\SliderEditType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Slider controller.
 *
 * @Route("/admin/slider")
 */
class SliderController extends Controller
{

    /**
     * Lists all Slider entities.
     *
     * @return array
     *
     * @Route("/", name="bor_slider_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BORCoreBundle:Slider')->findAll();
        $nbRows = count($entities);

        return array(
            'entities' => $entities,
            'nbRows' => $nbRows,
        );
    }
    /**
     * Creates a new Slider entity.
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="bor_slider_create")
     * @Method("POST")
     * @Template("BORCoreBundle:Slider:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $media = new Media();
        $slider = new Slider();
        $slider->setMedia($media);

        $form = $this->createCreateForm($slider);

        $params = $request->get('bor_corebundle_slider');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                if (isset($params['active'])) {
                    $slider->setActive(true);
                } else {
                    $slider->setActive(false);
                }

                $media->upload();

                $em->persist($media);
                $em->persist($slider);
                $em->flush();

                return $this->redirect($this->generateUrl('bor_slider_show', array('id' => $slider->getId())));
            }
        }

        return array(
            'entity' => $slider,
            'media' => $media,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Slider entity.
     *
     * @param Slider $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Slider $entity)
    {
        $form = $this->createForm(new SliderType(), $entity, array(
            'action' => $this->generateUrl('bor_slider_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Slider entity.
     *
     * @return array
     *
     * @Route("/new", name="bor_slider_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Slider();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Slider entity.
     * @param mixed $id The entity id
     *
     * @return array
     *
     * @Route("/{id}", name="bor_slider_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORCoreBundle:Slider')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Slider entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Slider entity.
     * @param mixed $id The entity id
     *
     * @return array
     *
     * @Route("/{id}/edit", name="bor_slider_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BORCoreBundle:Slider')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Slider entity.');
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
    * Creates a form to edit a Slider entity.
    *
    * @param Slider $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Slider $entity)
    {
        $form = $this->createForm(new SliderEditType(), $entity, array(
            'action' => $this->generateUrl('bor_slider_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Slider entity.
     * @param Request $request
     * @param mixed   $id The entity id
     *
     * @return array
     *
     * @Route("/{id}", name="bor_slider_update")
     * @Method("POST")
     * @Template("BORCoreBundle:Slider:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $slider = $em->getRepository('BORCoreBundle:Slider')->find($id);

        if (!$slider) {
            throw $this->createNotFoundException('Unable to find Slider entity.');
        }

        $oldMedia = $slider->getMedia();

        $media = new Media();
        $slider->setMedia($media);

        $params = $request->get('bor_corebundle_slider');

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($slider);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if (isset($params['active'])) {
                $slider->setActive(true);
            } else {
                $slider->setActive(false);
            }

            if (isset($request->files->get('bor_corebundle_slider')['media']['file'])) {
                // si la photo a été changée, on upload le nouveau media
                $media->upload();
                $em->persist($media);
                // une fois l'upload effectué on supprime l'ancien média
                if ($oldMedia) {
                    $em->remove($oldMedia);
                }
            } else {
                $slider->setMedia($oldMedia);
            }

            $em->persist($slider);
            $em->flush();

            return $this->redirect($this->generateUrl('bor_slider_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $slider,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Slider entity.
     * @param Request $request
     * @param mixed   $id The entity id
     *
     * @return RedirectResponse
     *
     * @Route("/{id}", name="bor_slider_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BORCoreBundle:Slider')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Slider entity.');
            }

            $em->flush();
        }

        $em->remove($entity);

        return $this->redirect($this->generateUrl('bor_slider_index'));
    }

    /**
     * Creates a form to delete a Slider entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bor_slider_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
