<?php

namespace App\Form;

use App\Entity\Meeting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\IntegerToLocalizedStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', TextType::class, [
                'label' => 'Sujet',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Sujet',
                    'class' => 'bg-white input_grey pb-3',
                    'style' => 'max-width:465px',
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description (Facultatif)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Veuillez saisir la description de votre réunion',
                    'class' => 'bg-white',
                    'rows' => 3,
                    'cols' => 20,
                    'style' => 'max-width:465px',
                ]
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy HH:mm',
                'attr' => [
                    'placeholder' => '00/00/0000',
                    'class' => 'bg-white pb-2',
                    'style' => 'max-width:150px',
                ],
            ])
            ->add('durationH', IntegerType::class, [
                'label' => 'h',
                'required' => false,
                'attr' => [
                    'class' => 'bg-white pb-2',
                    'placeholder' => '00',
                ]
            ])
            ->add('durationM', IntegerType::class, [
                'label' => 'min',
                'required' => false,
                'attr' => [
                    'class' => 'bg-white pb-2',
                    'placeholder' => '00',
                ]
            ])
            ->add('password', TextType::class, [
                'label' => 'Mot de passe requis pour accéder à la réunion',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Mot de passe',
                    'class' => 'bg-white pb-3',
                    'style' => 'max-width:250px',
                ]
            ])
            ->add('participants', CollectionType::class, [
                'label' => false,
                'entry_type' => ParticipantType::class,
                'block_prefix' => 'participants_entry',
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Meeting::class,
        ]);
    }
}
