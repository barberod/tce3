<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluationAnnotateType extends AbstractType
{

		public function buildForm(
			FormBuilderInterface $builder,
			array $options
		): void {
				$builder
					->add('visibleNote', ChoiceType::class, [
						'label' => 'Shall this note be visible to the requester?',
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
					->add('noteBody', TextareaType::class, [
						'label' => 'Note',
						'required' => false,
					]);
		}

}
