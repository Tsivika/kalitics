<?php

namespace App\Form;

use App\Entity\Chantier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChantierType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom chantier',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer le nom',
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Le nom doit être supérieur à {{ limit }} caractères',
                        'max' => 150,
                    ]),
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Adresse chantier',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer adresse chantier',
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'L\'adresse doit être supérieur à {{ limit }} caractères',
                        'max' => 150,
                    ]),
                ],
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    'placeholder' => '00/00/0000',
                    'class' => 'bg-white pb-2',
                    'style' => 'max-width:150px',
                ],
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Chantier::class,
        ]);
    }
}
