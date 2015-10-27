<?php

namespace BOR\UserBundle\Controller;

use BOR\UserBundle\Entity\User;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormError;

/**
 * Class RegistrationController
 *
 * @package BOR\UserBundle\Controller
 */
class RegistrationController extends BaseController
{
    /**
     * Override de la fonction d'inscription de fos user
     *
     * @param Request $request
     *
     * @return null|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);
        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        if ($request->get('expert')) {
            $form
                ->add('job', 'text', array(
                    'label' => 'Fonction : ',
                    'required' => false
                ))
                ->add('website', 'text', array(
                    'label' => 'Votre site : ',
                    'required' => false
                ))
                ->add('presentationVideo', 'text', array(
                    'label' => 'Votre id de vidéo de présentation (fin d\'url youtube) : ',
                    'required' => false
                ))
                ->add('description', 'textarea', array(
                    'label' => 'Description : ',
                    'required' => false
                ))
                ->remove('isExpert')
                ->add('isExpert', 'hidden', array(
                    'mapped' => false,
                    'data' => '1',
                ));
        }
        $form->setData($user);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            //Attribution du rôle en fonction de la checkbox.
            if ($form->get('isExpert')->getData()) {
                $user->setType('expert');
                $user->setRoles(array('ROLE_EXPERT'));
            } else {
                $user->setType('user');
                $user->setRoles(array('ROLE_USER'));
            }
            //Si un email de parrainage est saisi alors on traite le cas
            if ($form->get('sponsorEmail')->getData()) {

                //Initialisation de doctrine
                $em = $this->getDoctrine()->getManager();
                $userRepository = $em->getRepository('UserBundle:User');

                //On vérifie que l'utilisateur existe bien.
                $sponsor = $userRepository->findOneByEmail($form->get('sponsorEmail')->getData());
                if (isset($sponsor)) {
                    $this->get('bor_core.contact')->sendMailAfterSponsoring($user, $sponsor);
                    $user->setSponsor($sponsor);
                } else {
                    //Si l'email du parrain est introuvable, on redirige l'utilisateur vers le formulaire en lui indiquant.
                    $form->get('sponsorEmail')->addError(new FormError('L\'email du parrain n\'est pas valide'));

                    return $this->render('FOSUserBundle:Registration:register.html.twig', array(
                        'form' => $form->createView(),
                    ));
                }
            }
            //envoi de mail
            $this->get('bor_core.contact')->sendMailAfterRegister($user);
            //Sauvegarde de l'utilisateur
            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('bor_core_index');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}