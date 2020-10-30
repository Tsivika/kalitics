<?php

namespace App\Form;

use App\Entity\VideoGuide;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

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
                ]
            ])
            ->add('url', TextType::class, [
                'label' => 'Vidéo',
                'attr' => [
                    'class' => 'form-control bg-white encart_home_body',
                    'placeholder' => 'Lien (ex: <iframe width=...)',
                    'required' => true,
                ]
            ])
            ->add('pdc', FileType::class, [
                'label' => 'Image de couverture',
                'attr' => [
                    'accept' => 'image/jpeg, image/jpg, image/png',
                    'required' => true,
                ],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'fichiers : jpg, jpeg, png sont valides',
                    ])
                ],
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
