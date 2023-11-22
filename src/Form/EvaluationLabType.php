<?php

namespace App\Form;

use App\Service\FormOptionsService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EvaluationLabType extends AbstractType
{
		private FormOptionsService $formOptionsService;

		public function __construct(
			FormOptionsService $formOptionsService
		) {
				$this->formOptionsService = $formOptionsService;
		}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
				$builder
					->add('labPrefix', TextType::class, [
						'label' => 'Lab Prefix',
						'required' => false,
						'mapped' => false,
						'constraints' => [
							new NotBlank(),
							new Length(['min' => 1, 'max' => 24]),
						],
						'help' => 'Example: "BIOL"',
					])
					->add('labNumber', TextType::class, [
						'label' => 'Lab Number',
						'required' => false,
						'mapped' => false,
						'constraints' => [
							new NotBlank(),
							new Length(['min' => 1, 'max' => 24]),
						],
						'help' => 'Example: "1013"',
					])
					->add('labTitle', TextType::class, [
						'label' => 'Lab Title',
						'required' => false,
						'mapped' => false,
						'constraints' => [
							new NotBlank(),
							new Length(['min' => 1, 'max' => 128]),
						],
						'help' => 'Example: "General Biology Laboratory"',
					])
					->add('labSemester', TextType::class, [
						'label' => 'Academic Term',
						'required' => false,
						'mapped' => false,
						'constraints' => [
							new NotBlank(),
							new Length(['min' => 1, 'max' => 128]),
						],
						'help' => 'Example: "Spring 2024"',
					])
					->add('labCreditBasis', ChoiceType::class, [
						'label' => 'Credit System',
						'choices' => [
							'Semester Hours' => 'Semester Hours',
							'Quarter Hours' => 'Quarter Hours',
							'Other/Units' => 'Other/Units',
						],
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'mapped' => false,
						'placeholder' => '- Select one -',
					])
					->add('labCreditHours', ChoiceType::class, [
						'label' => 'Credit Hours',
						'choices' => $this->formOptionsService->getCreditHourOptions(),
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'mapped' => false,
						'placeholder' => '- Select one -',
					])
					->add('labSyllabus', FileType::class, [
						'label' => 'Lab Syllabus',
						'required' => false,
						'mapped' => false,
					])
					->add('labDocument', FileType::class, [
						'label' => 'Lab Document',
						'required' => false,
						'mapped' => false,
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
