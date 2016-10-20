<?php

namespace LouvreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class ConsultType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('orderName', TextType::class, array(
                'label' => 'order.name',
            ))
            ->add('orderEmail', EmailType::class, array(
                'label' => 'order.email',
            ))
        ;
    }

}
