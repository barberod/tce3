<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class EvaluationLookUpRequesterType extends AbstractType
{
	public function __construct() {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('lookUpRequester', ChoiceType::class, [
            'label' => 'Do you want to refresh the requester\'s information now?',
            'choices' => [
                'No' => 'No',
                'Yes' => 'Yes',
            ],
            'expanded' => false,
            'multiple' => false,
            'required' => false,
            'placeholder' => '- Select one -',
            'data' => 'Yes',
        ])
        ;
    }
}
