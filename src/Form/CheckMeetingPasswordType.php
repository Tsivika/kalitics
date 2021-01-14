<?php

namespace App\Form;

use App\Model\PasswordModel;
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
                    'placeholder' => 'Mot de passe',
                    'class' => 'bg-white border-md infoPlaceholder pr-5 w-50',
                    'style' => 'padding-bottom:15px; display:initial'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            ['data_class' => PasswordModel::class]
        );
    }
}
