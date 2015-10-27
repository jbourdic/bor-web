<?php

namespace BOR\GamificationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class StatisticsType
 *
 * @package BOR\GamificationBundle\Form
 */
class StatisticsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('level')
            ->add('experience')
            ->add('levelExperience')
            ->add('credit')
            ->add('qcm')
            ->add('videos')
            ->add('createdOn')
            ->add('updatedOn')
            ->add('user');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BOR\GamificationBundle\Entity\Statistics'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bor_gamificationbundle_statistics';
    }
}
