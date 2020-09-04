<?php

namespace App\Form;

use App\Entity\Parameter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParameterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('videoModerator', null, [
                'label' => false
            ])
            ->add('videoParticipant', null, [
                'label' => false
            ])
            ->add('phonePwd', null, [
                'label' => false
            ])
            ->add('soundParticipant', null, [
                'label' => false
            ])
            ->add('messagePublic', null, [
                'label' => false
            ])
            ->add('annotationParticipant', null, [
                'label' => false
            ])
            ->add('boardParticipant', null, [
                'label' => false
            ])
            ->add('recordAuto', null, [
                'label' => false
            ])
            ->add('feedback', null, [
                'label' => false
            ])
            ->add('meetingReminder', null, [
                'label' => false
            ])
            ->add('meetingCanceled', null, [
                'label' => false
            ])
            ->add('personalMailbox', null, [
                'label' => false
            ])
            ->add('formatHtmlMail', null, [
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Parameter::class,
        ]);
    }
}
