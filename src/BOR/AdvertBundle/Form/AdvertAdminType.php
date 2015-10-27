<?php

namespace BOR\AdvertBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class AdvertAdminType
 *
 * @package BOR\AdvertBundle\Form
 */
class AdvertAdminType extends AdvertType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('active', 'checkbox', array('required'  => false));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bor_admin_advertbundle_advert';
    }
}
