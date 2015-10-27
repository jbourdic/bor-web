<?php

namespace BOR\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use BOR\MediaBundle\Form\MediaType;

/**
 * Class PostType
 *
 * @package BOR\BlogBundle\Form
 */
class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content', 'textarea', array('attr' => array('class'=> 'ckeditor')))
            ->add('type', 'choice', array(
                'label' => 'Type : ',
                'choices'   => array('news' => 'News', 'renovate' => 'Renovate'),
                'required'  => true,
            ))
            ->add('active', 'checkbox', array('mapped' => true,
                'label' => 'Active',
                'required' => false)
            )
            ->add('metaTitle', 'textarea', array('required' => false))
            ->add('metaDescription', 'textarea', array('required' => false));
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
        return 'bor_blogbundle_post';
    }
}
