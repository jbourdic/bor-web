<?php

namespace BOR\GamificationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ActionType
 *
 * @package BOR\GamificationBundle\Form
 */
class ActionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array())
            ->add('description', 'textarea')
            ->add('credit', 'integer')
            ->add('creditMin', 'integer')
            ->add('creditMax', 'integer')
            ->add('experience', 'integer')
            ->add('experienceMin', 'integer')
            ->add('experienceMax', 'integer');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BOR\GamificationBundle\Entity\Action'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bor_gamificationbundle_action';
    }
}
