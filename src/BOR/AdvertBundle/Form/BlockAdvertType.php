<?php

namespace BOR\AdvertBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class BlockAdvertType
 *
 * @package BOR\AdvertBundle\Form
 */
class BlockAdvertType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reason', 'textarea');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BOR\AdvertBundle\Entity\BlockAdvert'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bor_advertbundle_blockadvert';
    }
}
