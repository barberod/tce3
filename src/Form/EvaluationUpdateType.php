<?php

namespace App\Form;

use App\Entity\Evaluation;
use App\Entity\Institution;
use App\Service\EvaluationFormDefaultsService;
use App\Service\FormOptionsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EvaluationUpdateType extends AbstractType
{
	private EntityManagerInterface $entityManager;
	private FormOptionsService $formOptionsService;

	public function __construct(
		EntityManagerInterface $entityManager,
		FormOptionsService $formOptionsService
	) {
		$this->entityManager = $entityManager;
		$this->formOptionsService = $formOptionsService;
	}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		$formDefaultsService = new EvaluationFormDefaultsService($this->entityManager);
		$formDefaults = $formDefaultsService->getEvaluationUpdateDefaults($options['evaluation']);

		$builder
			->add('created', DateTimeType::class, [
				'label' => 'DateTime',
				'disabled' => true,
				'with_seconds' => false,
				'widget' => 'single_text',
				'input' => 'datetime',
				'required' => false,
				'data' => $formDefaults['created'],
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
				'data' => $formDefaults['requiredForAdmission'],
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
				'data' => $formDefaults['hasLab'],
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
				'data' => $formDefaults['locatedUsa'],
			])
			->add('state', ChoiceType::class, [
				'choices' => $this->formOptionsService->getUsStateOptions(),
				'placeholder' => 'Select a state',
				'expanded' => false,
				'multiple' => false,
				'required' => false,
				'data' => $formDefaults['state'],
			])
			->add('institution', ChoiceType::class, [
				'label' => 'Institution',
				'placeholder' => 'Select an institution',
				'expanded' => false,
				'multiple' => false,
				'required' => false,
				'choices' => [],
				'data' => $formDefaults['institution'],
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
				'data' => $formDefaults['institutionListed'],
			])
			->add('country', ChoiceType::class, [
				'choices' => $this->formOptionsService->getCountryOptions(),
				'label' => 'Country',
				'expanded' => false,
				'multiple' => false,
				'placeholder' => 'Select a country',
				'required' => false,
				'data' => $formDefaults['institutionCountry'],
			])
			->add('institutionName', TextType::class, [
				'label' => 'Name of Institution',
				'required' => false,
				'help' => 'Example: "Oxford University"',
				'data' => $formDefaults['institutionName'],
			])
			->add('courseSubjCode', TextType::class, [
				'label' => 'Course Prefix',
				'required' => false,
				'help' => 'Example: "BIOL"',
				'data' => $formDefaults['courseSubjCode'],
			])
			->add('courseCrseNum', TextType::class, [
				'label' => 'Course Number',
				'required' => false,
				'help' => 'Example: "1012"',
				'data' => $formDefaults['courseCrseNum'],
			])
			->add('courseTitle', TextType::class, [
				'label' => 'Course Title',
				'required' => false,
				'help' => 'Example: "General Biology"',
				'data' => $formDefaults['courseTitle'],
			])
			->add('courseTerm', TextType::class, [
				'label' => 'Academic Term',
				'required' => false,
				'help' => 'Example: "Fall 2023"',
				'data' => $formDefaults['courseTerm'],
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
				'data' => $formDefaults['courseCreditBasis'],
			])
			->add('courseCreditHours', ChoiceType::class, [
				'label' => 'Credit Hours',
				'choices' => $this->formOptionsService->getCreditHourOptions(),
				'expanded' => false,
				'multiple' => false,
				'required' => false,
				'placeholder' => '- Select one -',
				'data' => $formDefaults['courseCreditHours'],
			])
			->add('labPrefix', TextType::class, [
				'label' => 'Lab Prefix',
				'required' => false,
				'help' => 'Example: "BIOL"',
				'data' => $formDefaults['labPrefix'],
			])
			->add('labNumber', TextType::class, [
				'label' => 'Lab Number',
				'required' => false,
				'help' => 'Example: "1013"',
				'data' => $formDefaults['labNumber'],
			])
			->add('labTitle', TextType::class, [
				'label' => 'Lab Title',
				'required' => false,
				'help' => 'Example: "General Biology Laboratory"',
				'data' => $formDefaults['labTitle'],
			])
			->add('labSemester', TextType::class, [
				'label' => 'Academic Term',
				'required' => false,
				'help' => 'Example: "Spring 2024"',
				'data' => $formDefaults['labSemester'],
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
				'data' => $formDefaults['labCreditBasis'],
			])
			->add('labCreditHours', ChoiceType::class, [
				'label' => 'Credit Hours',
				'choices' => $this->formOptionsService->getCreditHourOptions(),
				'expanded' => false,
				'multiple' => false,
				'required' => false,
				'placeholder' => '- Select one -',
				'data' => $formDefaults['labCreditHours'],
			])
		;

		// Add event listener to handle dynamic population of university choices based on the selected state
		$builder->addEventListener(
			FormEvents::PRE_SUBMIT,
			function (FormEvent $event) {
				$form = $event->getForm();
				$data = $event->getData();

				if (isset($data['state'])) {
					$institutions = $this->formOptionsService->getInstitutionsByUSState($data['state']);
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

    public function configureOptions(OptionsResolver $resolver): void
		{
				$resolver->setDefaults([
					'evaluation' => null,
				]);
		}
}
