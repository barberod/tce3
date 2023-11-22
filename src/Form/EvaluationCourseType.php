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

class EvaluationCourseType extends AbstractType
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
					->add('coursePrefix', TextType::class, [
						'label' => 'Course Prefix',
						'required' => true,
						'mapped' => false,
						'constraints' => [
							new NotBlank(),
							new Length(['min' => 1, 'max' => 24]),
						],
						'help' => 'Example: "BIOL"',
					])
					->add('courseNumber', TextType::class, [
						'label' => 'Course Number',
						'required' => true,
						'mapped' => false,
						'constraints' => [
							new NotBlank(),
							new Length(['min' => 1, 'max' => 24]),
						],
						'help' => 'Example: "1012"',
					])
					->add('courseTitle', TextType::class, [
						'label' => 'Course Title',
						'required' => true,
						'mapped' => false,
						'constraints' => [
							new NotBlank(),
							new Length(['min' => 1, 'max' => 128]),
						],
						'help' => 'Example: "General Biology"',
					])
					->add('courseSemester', TextType::class, [
						'label' => 'Academic Term',
						'required' => true,
						'mapped' => false,
						'constraints' => [
							new NotBlank(),
							new Length(['min' => 1, 'max' => 128]),
						],
						'help' => 'Example: "Fall 2023"',
					])
					->add('courseCreditBasis', ChoiceType::class, [
						'label' => 'Credit System',
						'choices' => [
							'Semester Hours' => 'Semester Hours',
							'Quarter Hours' => 'Quarter Hours',
							'Other/Units' => 'Other/Units',
						],
						'expanded' => false,
						'multiple' => false,
						'required' => true,
						'mapped' => false,
						'placeholder' => '- Select one -',
					])
					->add('courseCreditHours', ChoiceType::class, [
						'label' => 'Credit Hours',
						'choices' => $this->formOptionsService->getCreditHourOptions(),
						'expanded' => false,
						'multiple' => false,
						'required' => true,
						'mapped' => false,
						'placeholder' => '- Select one -',
					])
					->add('courseSyllabus', FileType::class, [
						'label' => 'Course Syllabus',
						'required' => true,
						'mapped' => false,
					])
					->add('courseDocument', FileType::class, [
						'label' => 'Course Document',
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
