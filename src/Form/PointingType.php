<?php

namespace App\Form;

use App\Entity\Chantier;
use App\Entity\Pointing;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PointingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    'placeholder' => '00/00/0000',
                    'class' => 'bg-white pb-2',
                    'style' => 'max-width:150px',
                ],
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée en heure',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Durée en heure',
                    'style' => 'max-width:150px',
                ]
            ])
            ->add('chantier', EntityType::class, [
                'label' => 'Chantier',
                'class' => Chantier::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'style' => 'width:350px',
                ]
            ])
            ->add('user', EntityType::class, [
                'label' => 'Utilisateur',
                'class' => User::class,
                'choice_label' => 'firstname',
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'style' => 'width:350px',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pointing::class,
        ]);
    }
}
