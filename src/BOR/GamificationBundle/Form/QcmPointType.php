<?php

namespace BOR\GamificationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class QcmPointType
 *
 * @package BOR\GamificationBundle\Form
 */
class QcmPointType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('qcmPoints', 'integer', array('read_only' => true))
            ->add('experience');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BOR\GamificationBundle\Entity\QcmPoint'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bor_gamificationbundle_qcmpoint';
    }
}
