<?php

namespace App\Form;

use App\Entity\Subscription;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class UserAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subscription', EntityType::class, [
                'label' => false,
                'class' => Subscription::class,
                'query_builder' => function (EntityRepository $er) {
                    $qb = $er->createQueryBuilder('s');
                        $qb->where('s.mode = ?1')
                        ->setParameter(1, 'paying');
                    return $qb;
                },
                'choice_label' => function(Subscription $sub) {
                    return sprintf('%s (%d€/mois)', $sub->getTitle(), $sub->getPrice());
                },
                'expanded' => true,
                'multiple' => false,
                'mapped' => false,
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control bg-white',
                    'placeholder' => 'Email',
                ]
            ])
            ->add('codePromo', TextType::class, [
                'label' => 'Code de réduction',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control bg-white',
                ]
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-white',
                    'placeholder' => 'Nom',
                ]
            ])
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-white',
                    'placeholder' => 'Prénom',
                ]
            ])
            ->add('address', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-white',
                    'placeholder' => 'Adresse',
                ]
            ])
            ->add('entreprise', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-white',
                    'placeholder' => 'Entreprise',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
