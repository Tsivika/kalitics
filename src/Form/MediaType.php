<?php

namespace App\Form;

use App\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'file',
                FileType::class,
                [
                    'label' => false,
                    'attr' => [
                        'accept' => 'image/jpeg, image/jpg',
                        'class' => 'ouvrage_photo_class',
                    ],
                    'mapped' => true,
                    'required' => false,
                    'constraints' => [
                        new File(
                            [
                                'maxSize' => '2048k',
                                'mimeTypes' => [
                                    'image/jpg',
                                    'image/jpeg',
                                    'image/png',
                                ],
                                'mimeTypesMessage' => 'Votre fichier n\'est pas correct. Format accépté : JPEG',
                                'maxSizeMessage' => 'Votre fichier image est trop volumineux. Sa taille ne doit pas dépasser 2Mo.',
                            ]
                        ),
                    ],
                ]
            );
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
