<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $customerId = $options['data']->getCustomer()->getId();

        $builder
            ->add('description', TextareaType::class)
            ->add('customer', 'entity', array(
                'class' => 'AppBundle\Entity\Customer',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) use ($customerId) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id = :customerId')
                        ->setParameter(':customerId', $customerId)
                        ->orderBy('c.name', 'ASC');
                },
            ))
            ->add('important', CheckboxType::class, array(
                'label'    => 'Important',
                'required' => false,
            ))
            ->add('user', 'entity', array(
                'class' => 'AppBundle\Entity\User',
                'choice_label' => 'fullName',
                'query_builder' => function (EntityRepository $er) use ($customerId) {
                    return $er->createQueryBuilder('u')
                        ->where('u.customer = :customer')
                        ->setParameter('customer', $customerId)
                        ->addOrderBy('u.lastName', 'ASC')
                        ->addOrderBy('u.firstName', 'ASC');
                },
            ))
            ->add('nextAt', DateTimeType::class, array(
                'attr' => array(
                    'class' => 'mdl-textfield__input datepicker-here',
                    'data-language' => 'en',
                    'data-timepicker' => true,
                    'readonly' => 'readonly'
                ),
                'widget' => 'single_text'
            ))
            ->add('modifiedAt', DateTimeType::class, array(
                'attr' => array(
                    'class' => 'mdl-textfield__input datepicker-here',
                    'data-language' => 'en',
                    'data-timepicker' => true,
                    'readonly' => 'readonly'
                ),
                'widget' => 'single_text'
            ))
            ->add('endedAt', DateTimeType::class, array(
                'attr' => array(
                    'class' => 'mdl-textfield__input datepicker-here',
                    'data-language' => 'en',
                    'data-timepicker' => true,
                    'readonly' => 'readonly'
                ),
                'widget' => 'single_text'
            ))
            ->add('cycleTime', 'entity', array(
                'class' => 'AppBundle\Entity\TaskCycle',
                'choice_label' => 'name',
            ))
            ->add('amount', IntegerType::class)
            ->add('status', 'entity', array(
                'class' => 'AppBundle\Entity\TaskStatus',
                'choice_label' => 'name',
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Task'
        ));
    }
}
