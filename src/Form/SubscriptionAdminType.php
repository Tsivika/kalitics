<?php

namespace App\Form;

use App\Constants\SubscriptionConstant;
use App\Entity\Subscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriptionAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom abonnement',
                'attr' => [
                    'class' => 'form-control bg-white',
                ],
            ])
            ->add('duration', ChoiceType::class, [
                'choices' => SubscriptionConstant::_DURATION_SUBSCRIPTION_,
                'label' => 'Abonnement de ',
                'required' => true,
            ])
            ->add('mode', ChoiceType::class, [
                'choices' => SubscriptionConstant::_MODE_SUBSCRIPTION_,
                'expanded' => true,
                'multiple' => false,
                'label' => 'Mode abonnement',
                'required' => true,
            ])
            ->add('duration_meeting', TextType::class, [
                'label' => 'Durée de la réunion',
                'attr' => [
                    'class' => 'form-control bg-white',
                ],
            ])
            ->add('numberParticipant', TextType::class, [
                'label' => 'Nombre de participants',
                'required' => false,
                'attr' => [
                    'class' => 'form-control bg-white',
                ],
            ])
            ->add('messagingInstant', null, [
                'label' => 'Messagerie instantanée',
            ])
            ->add('screenSharing', null, [
                'label' => "Partage d'écran en direct",
            ])
            ->add('recordingMeeting', null, [
                'label' => "Enregistrement des réunions",
            ])
            ->add('reminderMeeting', null, [
                'label' => "Rappel automatique des réunions par mail",
            ])
            ->add('price', TextType::class, [
                'label' => 'Prix abonnement',
                'required' => false,
                'attr' => [
                    'class' => 'form-control bg-white',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subscription::class,
        ]);
    }
}
