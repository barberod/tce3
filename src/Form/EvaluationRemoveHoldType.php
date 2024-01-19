<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluationRemoveHoldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('phase', ChoiceType::class, [
                'label' => 'To which phase are you sending this evaluation?',
                'choices' => [
                    'Student' => 'Student',
                    'Registrar 1' => 'Registrar 1',
                    'Department' => 'Department',
                    'Registrar 2' => 'Registrar 2',
                    'Complete' => 'Complete',
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => false,
            ])
            ->add('addNote', ChoiceType::class, [
                'label' => 'Add a note?',
                'choices' => [
                    'No' => 'No',
                    'Yes' => 'Yes',
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'placeholder' => '- Select one -',
                'data' => 'No',
            ])
            ->add('noteBody', TextareaType::class, [
                'label' => 'Note',
                'required' => false,
            ]);
        ;
    }
}
