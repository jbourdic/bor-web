<?php

namespace BOR\UserBundle\Controller;


use FOS\UserBundle\Controller\ProfileController as BaseController;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use BOR\MediaBundle\Entity\Media;

/**
 * ProfileController
 */
class ProfileController extends BaseController
{
    /**
     * Edit the user
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $media = new Media();
        $oldMedia = $user->getMedia();
        $user->setMedia($media);

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        if ($user->getType() === 'user') {
            $form
                ->remove('job')
                ->remove('website')
                ->remove('presentationVideo')
                ->remove('description');
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            $em = $this->getDoctrine()->getManager();

            if (isset($request->files->get('fos_user_profile_form')['media']['file'])) {
                // si la photo a été changée, on upload le nouveau media
                $media->upload();
                $em->persist($media);
                // une fois l'upload effectué on supprime l'ancien média
                if ($oldMedia) {
                    $em->remove($oldMedia);
                }
            } else {
                $user->setMedia($oldMedia);
            }

            $em->flush();

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_profile_show');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        $user->setMedia($oldMedia);
        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
            'user' => $user,
            'form' => $form->createView()
        ));
    }
}
