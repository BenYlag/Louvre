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
            ->add('name', TextType::class, array(
                'label' => 'ticket.name',
            ))
            ->add('surname', TextType::class, array(
                'label' => 'ticket.surname',
            ))
            ->add('birth', DateType::class, array(
                'years' => range(date('Y') - 110, date('Y')),
                'label' => 'ticket.birth',
            ))
            ->add('country', CountryType::class, array(
                'preferred_choices' => array('FR', 'GB', 'DE', 'ES'),
                'label' => 'ticket.country',
            ))
            ->add('discount', CheckboxType::class, array(
                'required' => false,
                'label' => 'ticket.discount',
                'label_attr' => array ('class' => 'discount'),
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LouvreBundle\Entity\Ticket',
        ));
    }
}
