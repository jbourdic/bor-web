<?php

namespace BOR\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class SponsorshipType
 *
 * @package BOR\UserBundle\Form
 */
class SponsorshipType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idSponsor')
            ->add('godsonEmail')
            ->add('hasAccepted')
            ->add('createdOn')
            ->add('updatedOn');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BOR\UserBundle\Entity\Sponsorship'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bor_userbundle_sponsorship';
    }
}
