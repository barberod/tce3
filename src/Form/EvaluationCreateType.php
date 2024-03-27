<?php

namespace App\Form;

use App\Service\FormOptionsService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class EvaluationCreateType extends AbstractType
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
			->add('submissionDateTime', DateTimeType::class, [
				'label' => 'Current DateTime',
				'disabled' => true,
				'data' => new \DateTime(),
				'with_seconds' => false,
				'widget' => 'single_text',
				'input' => 'datetime',
				'required' => false,
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
				'placeholder' => '- Select one -',
				'data' => 'No',
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
				'placeholder' => '- Select one -',
				'data' => 'No',
			])
			->add('locatedUsa', ChoiceType::class, [
				'label' => 'Is the institution located in the United States?',
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
			->add('state', ChoiceType::class, [
				'choices' => $this->formOptionsService->getUsStateOptions(),
				'placeholder' => 'Select a state',
				'expanded' => false,
				'multiple' => false,
				'required' => false,
			])
			->add('institution', ChoiceType::class, [
				'label' => 'Institution',
				'placeholder' => 'Select an institution',
				'expanded' => false,
				'multiple' => false,
				'required' => false,
				'choices' => [], // Initially empty, will be dynamically populated
			])
			->add('institutionListed', ChoiceType::class, [
				'label' => 'Is the institution listed?',
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
			->add('country', ChoiceType::class, [
				'choices' => $this->formOptionsService->getCountryOptions(),
				'label' => 'Country',
				'expanded' => false,
				'multiple' => false,
				'placeholder' => 'Select a country',
				'required' => false,
			])
			->add('institutionName', TextType::class, [
				'label' => 'Name of Institution',
				'required' => false,
				'help' => 'Example: "Oxford University"',
			])
			->add('coursePrefix', TextType::class, [
				'label' => 'Course Prefix',
				'required' => false,
				'help' => 'Example: "BIOL"',
			])
			->add('courseNumber', TextType::class, [
				'label' => 'Course Number',
				'required' => false,
				'help' => 'Example: "1012"',
			])
			->add('courseTitle', TextType::class, [
				'label' => 'Course Title',
				'required' => false,
				'help' => 'Example: "General Biology"',
			])
			->add('courseSemester', TextType::class, [
				'label' => 'Academic Term',
				'required' => false,
				'help' => 'Example: "Fall 2023"',
			])
			->add('courseCreditBasis', ChoiceType::class, [
				'label' => 'Credit System',
				'choices' => [
					'Semester Hours' => 'Semester',
					'Quarter Hours' => 'Quarter',
					'Other/Units' => 'Other',
				],
				'expanded' => false,
				'multiple' => false,
				'required' => false,
				'placeholder' => '- Select one -',
			])
			->add('courseCreditHours', ChoiceType::class, [
				'label' => 'Credit Hours',
				'choices' => $this->formOptionsService->getCreditHourOptions(),
				'expanded' => false,
				'multiple' => false,
				'required' => false,
				'placeholder' => '- Select one -',
			])
			->add('courseSyllabus', FileType::class, [
				'label' => 'Course Syllabus',
				'required' => false,
			])
			->add('courseDocument', FileType::class, [
				'label' => 'Course Document',
				'required' => false,
			])
			->add('labPrefix', TextType::class, [
				'label' => 'Lab Prefix',
				'required' => false,
				'help' => 'Example: "BIOL"',
			])
			->add('labNumber', TextType::class, [
				'label' => 'Lab Number',
				'required' => false,
				'help' => 'Example: "1013"',
			])
			->add('labTitle', TextType::class, [
				'label' => 'Lab Title',
				'required' => false,
				'help' => 'Example: "General Biology Laboratory"',
			])
			->add('labSemester', TextType::class, [
				'label' => 'Academic Term',
				'required' => false,
				'help' => 'Example: "Spring 2024"',
			])
			->add('labCreditBasis', ChoiceType::class, [
				'label' => 'Credit System',
				'choices' => [
					'Semester Hours' => 'Semester',
					'Quarter Hours' => 'Quarter',
					'Other/Units' => 'Other',
				],
				'expanded' => false,
				'multiple' => false,
				'required' => false,
				'placeholder' => '- Select one -',
			])
			->add('labCreditHours', ChoiceType::class, [
				'label' => 'Credit Hours',
				'choices' => $this->formOptionsService->getCreditHourOptions(),
				'expanded' => false,
				'multiple' => false,
				'required' => false,
				'placeholder' => '- Select one -',
			])
			->add('labSyllabus', FileType::class, [
				'label' => 'Lab Syllabus',
				'required' => false,
			])
			->add('labDocument', FileType::class, [
				'label' => 'Lab Document',
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
			])
			->add('attest', ChoiceType::class, [
				'label' => 'Attestation',
				'choices' => [
					'Yes' => 'Yes',
				],
				'expanded' => false,
				'multiple' => false,
				'required' => false,
				'placeholder' => '---',
			])
		;

		// Add event listener to handle dynamic population of university choices based on the selected state
		$builder->addEventListener(
			FormEvents::PRE_SUBMIT,
			function (FormEvent $event) {
				$form = $event->getForm();
				$formData = $event->getData();

				if (isset($formData['state'])) {
					$institutions = $this->formOptionsService->getInstitutionsByUSState($formData['state']);
					$form->add('institution', ChoiceType::class, [
						'label' => 'Institution',
						'placeholder' => 'Select an institution',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'choices' => $institutions,
					]);
				}
			}
		);
    }
}
