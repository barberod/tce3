<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluationAnnotateAsRequesterType extends AbstractType
{
		public function buildForm(FormBuilderInterface $builder, array $options): void {
				$builder
					->add('noteBody', TextareaType::class, [
						'label' => 'Note',
						'required' => false,
					]);
		}
}
