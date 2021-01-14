<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Guide;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Question ',
                    'class' => 'form-control bg-white',
                    'style' => 'width:350px',
                ]
            ])
            ->add('response', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Réponse',
                    'class' => 'form-control bg-white',
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => false,
                'class' => Category::class,
                'choice_label' => 'title',
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'style' => 'width:350px',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Guide::class,
        ]);
    }
}
