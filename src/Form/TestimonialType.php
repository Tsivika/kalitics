<?php

namespace App\Form;

use App\Entity\Testimonial;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TestimonialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control bg-white encart_home_body',
                    'placeholder' => 'Nom',
                ]
            ])
            ->add('score', IntegerType::class, [
                'label' => 'Note',
                'attr' => [
                    'class' => 'form-control bg-white encart_home_body',
                    'placeholder' => 'Note',
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'required' => false,
                'attr' => [
                    'class' => 'form-control bg-white encart_home_body ',
                    'placeholder' => 'Avis (maxi : 200 caractères)',
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Image de couverture',
                'attr' => [
                    'accept' => 'image/jpeg, image/jpg, image/png',
                ],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'fichiers : jpg, jpeg, png sont valides',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Testimonial::class,
        ]);
    }
}
