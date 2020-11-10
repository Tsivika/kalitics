<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendEmailToUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-white encart_home_body ',
                    'placeholder' => 'Sujet'
                ]
            ])
            ->add('message', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control bg-white encart_home_body ',
                    'placeholder' => 'Message',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
