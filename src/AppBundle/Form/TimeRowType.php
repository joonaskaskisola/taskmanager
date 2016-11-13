<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeRowType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextareaType::class)
            ->add('date', DateType::class, array(
                'attr' => array(
                    'class' => 'mdl-textfield__input',
                    'data-language' => 'en',
                    'readonly' => 'readonly'
                ),
                'widget' => 'single_text'
            ))
            ->add('user', 'entity', array(
                'class' => 'AppBundle\Entity\User',
                'choice_label' => 'fullName',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->addOrderBy('u.lastName', 'ASC')
                        ->addOrderBy('u.firstName', 'ASC');
                },
            ))
            ->add('startTime', 'time', array(
                'minutes' => array(
                    '0', '10', '20', '30', '40', '50'
                )
            ))
            ->add('endTime', 'time', array(
                'minutes' => array(
                    '0', '10', '20', '30', '40', '50'
                )
            ))
            ->add('lunch', 'time', array(
                'hours' => array(
                    '0', '1', '2', '3'
                ),
                'minutes' => array(
                    '0', '15', '30', '45'
                )
            ))
            ->add('sickleave', 'time')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\TimeRow'
        ));
    }
}
