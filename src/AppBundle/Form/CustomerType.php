<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('name2', TextType::class)
            ->add('email', TextType::class)
            ->add('streetAddress', TextType::class)
            ->add('zipCode', TextType::class)
            ->add('locality', TextType::class)
            ->add('country', TextType::class)
            ->add('businessId', TextType::class)
//            ->add('createdAt', DateTimeType::class, array(
//                'attr' => array(
//                    'class' => 'mdl-textfield__input datepicker-here',
//                    'data-language' => 'en',
//                    'data-timepicker' => true,
//                    'readonly' => 'readonly'
//                ),
//                'widget' => 'single_text'
//            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Customer'
        ));
    }
}
