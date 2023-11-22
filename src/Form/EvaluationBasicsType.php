<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluationBasicsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
				$builder
					->add('submissionDateTime', DateTimeType::class, [
						'label' => 'Current DateTime',
						'disabled' => true,
						'data' => new \DateTime(),
						'with_seconds' => false,
						'widget' => 'single_text',
						'input' => 'datetime',
					])
					->add('requiredForAdmission', ChoiceType::class, [
						'label' => 'Is this course required for admission?',
						'choices' => [
							'No' => 'No',
							'Yes' => 'Yes',
						],
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'mapped' => false,
						'placeholder' => '- Select one -',
					])
					->add('hasLab', ChoiceType::class, [
						'label' => 'Does this course have an associated lab?',
						'choices' => [
							'No' => 'No',
							'Yes' => 'Yes',
						],
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'mapped' => false,
						'placeholder' => '- Select one -',
					])
				;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
