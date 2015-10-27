<?php

namespace BOR\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class UserAdminType
 *
 * @package BOR\UserBundle\Form
 */
class UserAdminType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('civility', 'choice', array(
                'label' => 'Civilité : ',
                'choices'   => array('Monsieur' => 'Monsieur', 'Madame' => 'Madame'),
                'required'  => true,
            ))
            ->add('firstname')
            ->add('lastname')
            ->add('phone')
            ->add('zipCode')
            ->add('type', 'choice', array( 'choices' => array('admin' => 'Admin', 'expert' => 'Expert', 'user' => 'User')))
            ->add('active', 'checkbox', array(
                'required' =>false,
            ))
            ->add('sponsor', 'email', array(
                'label' => 'Adresse email du parrain : ',
                'mapped' => false,
                'required' =>false
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
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BOR\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bor_admin_userbundle_user';
    }
}
