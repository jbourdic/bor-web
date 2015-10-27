<?php

namespace BOR\GamificationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class LevelType
 *
 * @package BOR\GamificationBundle\Form
 */
class LevelType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('level', 'integer', array('read_only' => true))
            ->add('experienceMin', 'integer', array('read_only' => true))
            ->add('experienceMax', 'integer', array('read_only' => true))
            ->add('experienceRequired', 'integer')
            ->add('creditReward', 'integer');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BOR\GamificationBundle\Entity\Level'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bor_gamificationbundle_level';
    }
}
