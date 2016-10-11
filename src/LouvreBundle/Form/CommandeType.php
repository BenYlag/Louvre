<?php

namespace LouvreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class)
            ->add('date', DateType::class, array(
                'widget' => 'single_text',
                //'data' => new \DateTime('tomorrow'),
                'format' => 'dd/MM/yyyy',
               // 'years' => range(date('Y'), date('Y') + 2),
                'attr' => array(
                    'class' => 'date'
                ),
            ))
            ->add('duree', CheckboxType::class, array(
                'required' => false
            ))
            ->add('tickets', CollectionType::class, array(
                'entry_type' => TicketType::class,
                'allow_add' => true,
                'allow_delete' => true
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LouvreBundle\Entity\Commande'
        ));
    }
}
