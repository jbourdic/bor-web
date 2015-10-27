<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BOR\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use BOR\MediaBundle\Form\MediaType;

/**
 * Class ProfileFormType
 *
 * @package BOR\UserBundle\Form\Type
 */
class ProfileFormType extends BaseType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildUserForm($builder, $options);

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'profile',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bor_user_profile';
    }

    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('civility', 'choice', array(
                'label' => 'Civilité : ',
                'choices'   => array('Monsieur' => 'Monsieur', 'Madame' => 'Madame'),
                'required'  => true,
            ))
            ->add('firstname', 'text', array(
                'label' => 'Prénom : ',
            ))
            ->add('lastname', 'text', array(
                'label' => 'Nom : ',
            ))
            ->add('phone', 'text', array(
                'label' => 'Téléphone : ',
            ))
            ->add('zipCode', 'text', array(
                'label' => 'Code Postal : ',
                'max_length' => 5,
            ))
            ->add('description', 'textarea', array(
                'label' => 'Description : ',
                'required' => false
            ))
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
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'required' => false,
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'Nouveau mot de passe'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ));


        $builder->add('media', new MediaType(), array(
                'label' => 'Photo de profil',
                'required' => false,
            ));
    }
}
