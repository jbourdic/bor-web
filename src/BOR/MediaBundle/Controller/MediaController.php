<?php

namespace BOR\MediaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BOR\MediaBundle\Entity\Media;
use BOR\MediaBundle\Form\MediaType;

/**
 * Media controller.
 */
class MediaController extends Controller
{

    /**
     * Lists all Media entities.
     *
     * @return array
     *
     * @Route("/mon-profil/ma-galerie/", name="bor_gallery")
     * @Route("/admin/galerie/", name="bor_admin_gallery")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em              = $this->getDoctrine()->getManager();
        $mediaRepository = $em->getRepository('BORMediaBundle:Media');

        $entities = $mediaRepository->findByUser($this->getUser());
        $template = 'BORMediaBundle:Media:index.html.twig';
        if (
            $this->get('request')->get('_route') == "bor_admin_gallery"
            && $this->get('security.context')->isGranted('ROLE_ADMIN')
        ) {
            $entities = $mediaRepository->findAll();
            $template = 'BORMediaBundle:Admin:Media/index.html.twig';
        }

        $entities = array_map(function($entity){
            $deleteForm = $this->createDeleteForm($entity->getId());

            return array('delete_form'=>$deleteForm->createView(), 'media'=>$entity);
        }, $entities);

        $entity = new Media();
        $form   = $this->createCreateForm($entity);

        return $this->render(
            $template,
            array(
                'entities' => $entities,
                'add_form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a new Media entity.
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @Route("/galerie/", name="bor_gallery_create")
     * @Method("POST")
     * @Template("BORMediaBundle:Media:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Media();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $entity->setUser($this->getUser());
            $entity->setCreatedOn();
            $entity->upload();

            $em->persist($entity);
            $em->flush();

            $this->get('bor_core.gamification')->gammificationAction($this->getUser(), 'uploadPhoto');
            return $this->redirect($this->generateUrl('bor_gallery'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Media entity.
     *
     * @param Media $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Media $entity)
    {
        $form = $this->createForm(new MediaType(), $entity, array(
            'action' => $this->generateUrl('bor_gallery_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Ajouter'));

        return $form;
    }

    /**
     * Deletes a Media entity.
     * @param Request $request
     * @param int     $id
     *
     * @return RedirectResponse
     *
     * @Route("/galerie/{id}", name="bor_gallery_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BORMediaBundle:Media')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Media entity.');
            }
            if ($entity->getUser()->getId() !== $this->getUser()->getId()) {
                throw new HttpException(403, "You can't delete other user's media");
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('bor_gallery'));
    }

    /**
     * Creates a form to delete a Media entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bor_gallery_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
