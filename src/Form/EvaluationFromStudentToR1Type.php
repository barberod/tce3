<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EvaluationFromStudentToR1Type extends AbstractType
{

		public function buildForm(
			FormBuilderInterface $builder,
			array $options
		): void {
				$builder
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
					->add('visibleNote', TextType::class, [
						'label' => 'Note Visibility',
						'disabled' => true,
						'data' => 'Visible',
						'required' => false,
						'help' => 'All new notes are visible to the requester.',
					])
					->add('noteBody', TextareaType::class, [
						'label' => 'Note',
						'required' => false,
					]);
		}

}

