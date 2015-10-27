<?php

namespace BOR\AdvertBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class FavoriteType
 *
 * @package BOR\AdvertBundle\Form
 */
class FavoriteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idUser')
            ->add('idAdvert');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BOR\AdvertBundle\Entity\Favorite'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bor_advert_favorite';
    }
}
