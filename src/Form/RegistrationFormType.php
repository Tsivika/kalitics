<?php

namespace App\Form;

use App\Constants\LanguageConstant;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control bg-white infoPlaceholder border-md encart_home_body py-3 pl-3 pr-5',
                    'placeholder' => 'Nom de famille',
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control bg-white infoPlaceholder border-md encart_home_body py-3 pr-5',
                    'placeholder' => 'Prénom',
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control bg-white infoPlaceholder border-md encart_home_body py-3 pr-5',
                    'placeholder' => 'Adresse mail'
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => false,
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])

            ->add('password', RepeatedType::class, [
                'label' => false,
                'type' => PasswordType::class,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Saisisser votre mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit être supérieur à {{ limit }} caractères',
                        'max' => 100,
                    ]),
                ],
                'invalid_message' => 'Les 2 mots de passe ne sont pas identiques.',
                'options' => ['attr' => ['class' => 'form-control bg-white border-md encart_home_body']],
                'required' => true,
                'first_options'  => ['label' => false, 'attr' => [
                    'placeholder' => 'Mot de passe',
                    'class' => 'form-control bg-white border-md infoPlaceholder encart_home_body py-3 pr-5',
                    ]],
                'second_options' => ['label' => false, 'attr' => ['placeholder' => 'Confirmer votre mot de passe',
                    'class' => 'form-control bg-white border-md infoPlaceholder encart_home_body py-3 pr-5'
                    ]],
            ])
            /*->add('language', ChoiceType::class,
                [
                    'label' => false,
                    'choices' => LanguageConstant::_LANGUAGES_,
                    'multiple' => false,
                    'expanded' => false,
                    'placeholder' => ':. Choix Langue .:',
                    'attr' => [
                        'class' => 'form-control bg-white border-md encart_home_body',
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez choisir au moins une langue.',
                        ]),
                    ],
                ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
