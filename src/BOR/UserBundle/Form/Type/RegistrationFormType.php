<?php
// src/BOR/UserBundle/Form/Type/RegistrationFormType.php
namespace BOR\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

/**
 * Extension du formulaire d'inscription de fos user
 * Class RegistrationFormType
 *
 * @package BOR\UserBundle\Form\Type
 */
class RegistrationFormType extends BaseType
{
    /**
     * Définition du formulaire.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //Récupération du formulaire de fos
        parent::buildForm($builder, $options);

        // Ajout des champs personnalisés
        $builder
            ->remove('username')
            ->remove('email')
            ->remove('plainPassword')
            ->add('email', 'email', array('label' => 'Email * :', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'Mot de passe * :'),
                'second_options' => array('label' => 'Verification du mot de passe * :'),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            ->add('civility', 'choice', array(
                'label' => 'Civilité * : ',
                'choices'   => array('Monsieur' => 'Monsieur', 'Madame' => 'Madame'),
                'required'  => true,
            ))
            ->add('firstname', 'text', array(
                'label' => 'Prénom * : ',
            ))
            ->add('lastname', 'text', array(
                'label' => 'Nom * : ',
            ))->add('zipCode', 'text', array(
                'label' => 'Code Postal * : ',
                'max_length' => 5,
            ))
            ->add('phone', 'text', array(
                'label' => 'Téléphone * : '
            ))
            ->add('sponsorEmail', 'email', array(
                'label' => 'Adresse email du parrain : ',
                'mapped' => false,
                'required' =>false
            ))
            ->add('isExpert', 'hidden', array(
                'mapped' => false,
                'data' => '0',
            ));
    }

    /**
     * Détermine le nom du formulaire à appeler
     *
     * @return string
     */
    public function getName()
    {
        return 'bor_user_registration';
    }
}
