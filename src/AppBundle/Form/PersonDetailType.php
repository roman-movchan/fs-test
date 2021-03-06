<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonDetailType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('iceCream')
            ->add('favSuperhero')
            ->add('favMovieStar')
            ->add('worldEnd', 'date', [
                    'input'  => 'datetime',
                    'widget' => 'choice',
                    'placeholder' => '',
            ])
            ->add('superBowl')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PersonDetail'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'person_detail_form';
    }
}
