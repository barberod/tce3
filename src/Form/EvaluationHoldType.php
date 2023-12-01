<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class EvaluationHoldType extends AbstractType
{
		public function buildForm(
			FormBuilderInterface $builder, array $options): void {
				$builder
					->add('holdForCourseInput', ChoiceType::class, [
						'label' => 'Add "Hold for Course Input" attribute?',
						'choices' => [
							'No' => 'No',
							'Yes' => 'Yes',
						],
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'placeholder' => '- Select one -',
					])
					->add('holdForTranscript', ChoiceType::class, [
						'label' => 'Add "Hold for Transcript" attribute?',
						'choices' => [
							'No' => 'No',
							'Yes' => 'Yes',
						],
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'placeholder' => '- Select one -',
					])
					->add('holdForRequesterAdmit', ChoiceType::class, [
						'label' => 'Add "Hold for Acceptance" attribute?',
						'choices' => [
							'No' => 'No',
							'Yes' => 'Yes',
						],
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'placeholder' => '- Select one -',
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
