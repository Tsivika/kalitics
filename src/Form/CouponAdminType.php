<?php

namespace App\Form;

use App\Entity\CodePromo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class CouponAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Titre',
                    'class' => 'form-control bg-white  border-md',
                ]
            ])
            ->add('reduction', IntegerType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Réduction (%)',
                    'class' => 'form-control bg-white  border-md',
                ]
            ])
            ->add('code', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Code',
                    'class' => 'form-control bg-white  border-md',
                ]
            ])
            ->add('status', CheckboxType::class, [
                'label' => 'Activer',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CodePromo::class,
        ]);
    }
}
