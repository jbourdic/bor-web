<?php

namespace BOR\AdvertBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class AdvertType
 *
 * @package BOR\AdvertBundle\Form
 */
class AdvertType extends AbstractType
{
    protected $gallery;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        for ($i=0; $i < 5; $i++) {
            $builder
                ->add('gallery'.$i, 'choice', array(
                    'choices'   => $this->gallery['options'],
                    'data'   => isset($this->gallery['data'][$i]) ? $this->gallery['data'][$i] : null,
                    'expanded'  => true,
                    'required' => false,
                ))
                ->add('upload'.$i, 'file', array('required' => false,));
        }
        $builder
            ->add('title', 'text')
            ->add('description', 'textarea')
            ->add('lodgmentType', 'choice', array('choices' => array('appartement' => 'Appartement', 'maison' => 'Maison', 'villa' => 'Villa', 'garage' => 'Garage', 'cave' => 'Cave', 'caravane' => 'Caravane', 'local' => 'Local', 'immeuble' => 'Immeuble', 'lotissement' => 'Lotissement')))
            ->add('transactType', 'choice', array('choices' => array('vente' => 'Vente', 'location' => 'Location')))
            ->add('surface', 'number')
            ->add('charges', 'money')
            ->add('price', 'money')
            ->add('tax', 'money')
            ->add('street', 'text')
            ->add('streetNumber', 'text')
            ->add('zipCode', 'text', array('max_length' => 5))
            ->add('city', 'text')
            ->add('country', 'text')
            ->add('photosphere', 'text', array('required' => false))
            ->add('metaTitle', 'textarea', array('required' => false))
            ->add('metaDescription', 'textarea', array('required' => false))
            ->add('active', 'checkbox', array('required' => false));
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

    /**
     * 
     */
    public function __construct($gallery)
    {
        $this->gallery = $gallery;
    }
}
