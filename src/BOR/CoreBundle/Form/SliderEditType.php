<?php

namespace BOR\CoreBundle\Form;

use BOR\MediaBundle\Form\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class SliderType
 *
 * @package BOR\CoreBundle\Form
 */
class SliderEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text')
            ->add('link')
            ->add('slideOrder', 'number')
            ->add('active', 'checkbox', array(
                'mapped' => true,
                'label' => 'Active',
                'required' => false
            ));

        $builder->add('media', new MediaType());
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BOR\CoreBundle\Entity\Slider'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bor_corebundle_slider';
    }
}
