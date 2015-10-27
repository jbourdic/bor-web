<?php

namespace BOR\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class AvailabilityType
 *
 * @package BOR\UserBundle\Form
 */
class AvailabilityType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idUser')
            ->add('day')
            ->add('beginTime')
            ->add('endTime')
            ->add('active')
            ->add('createdOn')
            ->add('updatedOn');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BOR\UserBundle\Entity\Availability'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bor_userbundle_availability';
    }
}
