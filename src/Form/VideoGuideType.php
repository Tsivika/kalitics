<?php

namespace App\Form;

use App\Entity\VideoGuide;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoGuideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'form-control bg-white encart_home_body',
                    'placeholder' => 'Titre',
                    'style' => 'width:450px',
                ]
            ])
            ->add('url', TextType::class, [
                'label' => 'Vidéo',
                'attr' => [
                    'class' => 'form-control bg-white encart_home_body',
                    'placeholder' => 'Lien',
                    'style' => 'width:450px',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VideoGuide::class,
        ]);
    }
}
