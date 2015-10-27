<?php

namespace BOR\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use BOR\MediaBundle\Form\MediaType;

/**
 * Class PostAdminType
 *
 * @package BOR\BlogBundle\Form
 */
class PostAdminType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('type', 'choice', array(
                'label' => 'Type : ',
                'choices'   => array('news' => 'News', 'renovate' => 'Renovate'),
                'required'  => true,
                ))
            ->add('active', 'checkbox', array('required' => false))
            ->add('metaTitle')
            ->add('metaDescription');
        $builder->add('media', new MediaType());
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BOR\BlogBundle\Entity\Post'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bor_admin_blogbundle_post';
    }
}
