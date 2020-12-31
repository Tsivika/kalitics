<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CheckMeetingPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', PasswordType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer le mot de passe',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre mot de passe doit être supérieur à {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nouveau mot de passe',
                    'class' => 'form-control bg-white border-md infoPlaceholder encart_home_body py-3 pr-5',
                ],
                'options' => ['attr' => ['class' => 'form-control bg-white border-md encart_home_body']],
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
