<?php

namespace App\Form;

use App\Constants\LanguageConstant;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control bg-white border-0',
                    'placeholder' => 'Nom de famille',
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control bg-white border-0',
                    'placeholder' => 'Prénom',
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => [
                    'class' => 'form-control bg-white border-0',
                    'placeholder' => 'Adresse mail',
                ]
            ])
            ->add('language', ChoiceType::class,
                [
                    'label' => 'Langue',
                    'choices' => LanguageConstant::_LANGUAGES_,
                    'multiple' => false,
                    'expanded' => false,
                    'placeholder' => ':. Choix Langue .:',
                    'attr' => [
                        'class' => 'form-control bg-white border-0',
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez choisir au moins une langue.',
                        ]),
                    ],
                ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'class' => 'form-control bg-white border-0',
                    'placeholder' => '************',
                ],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit être supérieur à {{ limit }} caractères',
                        'max' => 100,
                    ]),
                ],
                'required' => true,
            ])
            ->add('pdp', FileType::class, [
                'label' => 'Modifier',
                'attr' => [
                    'accept' => 'image/jpeg, image/jpg, image/png',
                    'class' => 'file-upload-input',
                    'onchange' => 'readURL(this);',
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
                        'maxSizeMessage' => 'Le fichier est trop volumineux ( {{ size }}{{ suffix }} ). La taille maximale autorisée est de {{ limit }}{{ suffix }}.',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
