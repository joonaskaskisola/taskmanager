<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerNoteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('createdAt', DateTimeType::class, array(
                'attr' => array(
                    'class' => 'mdl-textfield__input datepicker-here',
                    'data-language' => 'en',
                    'data-timepicker' => true,
                    'readonly' => 'readonly'
                ),
                'widget' => 'single_text'
            ))
            ->add('customer', 'entity', array(
                'class' => 'AppBundle\Entity\Customer',
                'choice_label' => 'name',
            ))
            ->add('creator', 'entity', array(
                'class' => 'AppBundle\Entity\User',
                'choice_label' => 'fullName',
            ))
            ->add('note')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\CustomerNote'
        ));
    }
}
