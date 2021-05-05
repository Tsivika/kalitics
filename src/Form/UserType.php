<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de famille',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer le nom de l\'utitlisateur',
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Le nom doit être supérieur à {{ limit }} caractères',
                        'max' => 150,
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Prénom',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer le prénom de l\'utitlisateur',
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Le prénom doit être supérieur à {{ limit }} caractères',
                        'max' => 150,
                    ]),
                ],
            ])
            ->add('registerNumber', IntegerType::class, [
                'label' => 'Matricule',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Matricule',
                ]
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
