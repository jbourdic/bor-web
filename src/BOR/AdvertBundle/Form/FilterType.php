<?php

namespace BOR\AdvertBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class FilterType
 *
 * @package BOR\AdvertBundle\Form
 */
class FilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod("GET")
            ->add('min', 'text', array("required" => false))
            ->add('max', 'text', array("required" => false))
            ->add('priceType', 'choice', array(
                'choices' => array('priceTTC' => 'Prix TCC', 'charges' => 'Charges', 'tax' => 'Frais'),
                'expanded' => true,
                'multiple' => false,
                'required' => false
            ))
            ->add('zipCode', 'text', array("required" => false))
            ->add('transactType', 'choice', array('choices' => array('all' => 'Tous', 'vente' => 'Vente', 'location' => 'Location')))
            ->add('order', 'choice', array('choices' => array('ascDate' => 'Du - récent au + récent', 'descDate' => 'Du + récent au - récent', 'ascPrice' => 'Du - cher au + cher', 'descPrice' => 'Du + cher au - cher')))
            ->add('submit', 'submit', array('label' => 'Rechercher en filtrant'));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BOR\AdvertBundle\Entity\Advert'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bor_advertbundle_advert';
    }

}
