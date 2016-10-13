<?php

namespace LouvreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TicketType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class, array(
                'disabled' => true
            ))
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('birth', DateType::class, array(
                'years' => range(date('Y') - 110, date('Y'))
            ))
            ->add('country', CountryType::class, array(
                //'choices' => Intl::getRegionBundle()->getCountryNames(),
                'preferred_choices' => array('FR', 'GB', 'DE', 'ES')
            ))
            ->add('discount', CheckboxType::class, array(
                'required' => false
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LouvreBundle\Entity\Ticket'
        ));
    }
}
