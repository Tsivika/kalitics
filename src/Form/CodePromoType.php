<?php

namespace App\Form;

use App\Entity\CodePromo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CodePromoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'attr' => [
                    'placeholder' => 'Titre',
                    'class' => 'form-control bg-white',
                ],
            ])
            ->add('reduction', null, [
                'attr' => [
                    'placeholder' => 'Reduction (%)',
                    'class' => 'form-control bg-white',
                ],
            ])
            ->add('status', null, [
                'label' => 'Activer: Non',
            ])
            ->add('code', null, [
                'attr' => [
                    'placeholder' => 'Code',
                    'class' => 'form-control bg-white',
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
            'data_class' => CodePromo::class,
        ]);
    }
}
