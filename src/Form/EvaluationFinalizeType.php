<?php

namespace App\Form;

use App\Service\EvaluationFormDefaultsService;
use App\Service\FormOptionsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluationFinalizeType extends AbstractType
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
				$formDefaults = $formDefaultsService->getEvaluationFinalizeDefaults($options['evaluation']);

				$builder
					->add('eqvCnt', ChoiceType::class, [
						'label' => 'How many GT courses will be referenced in this equivalency?',
						'choices' => [
							'0' => '0',
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
						],
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'placeholder' => '- Select one -',
						'data' => $formDefaults['eqvCnt'],
					])
					->add('eqv1SubjCode', ChoiceType::class, [
						'label' => 'GT Subject Code',
						'placeholder' => '- Select one -',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'choices' => $this->formOptionsService->getSubjectCodeOptions(),
						'data' => $formDefaults['eqv1SubjCode'],
					])
					->add('eqv1', ChoiceType::class, [
						'label' => 'GT Course',
						'placeholder' => '- Select one -',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'choices' => $formDefaults['eqv1Options'],
						'data' => $formDefaults['eqv1'],
					])
					->add('eqv1Hrs', ChoiceType::class, [
						'label' => 'GT Credit Hours',
						'choices' => $this->formOptionsService->getCreditHourOptions(),
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'placeholder' => '- Select one -',
						'data' => $formDefaults['eqv1Hrs'],
					])
					->add('eqv1Opr', ChoiceType::class, [
						'label' => 'Operator',
						'choices' => [
							'AND' => 'AND',
							'OR' => 'OR',
						],
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'placeholder' => '- None -',
						'data' => $formDefaults['eqv1Opr'],
					])
					->add('eqv2SubjCode', ChoiceType::class, [
						'label' => 'GT Subject Code',
						'placeholder' => '- Select one -',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'choices' => $this->formOptionsService->getSubjectCodeOptions(),
						'data' => $formDefaults['eqv2SubjCode'],
					])
					->add('eqv2', ChoiceType::class, [
						'label' => 'GT Course',
						'placeholder' => '- Select one -',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'choices' => $formDefaults['eqv2Options'],
						'data' => $formDefaults['eqv2'],
					])
					->add('eqv2Hrs', ChoiceType::class, [
						'label' => 'GT Credit Hours',
						'choices' => $this->formOptionsService->getCreditHourOptions(),
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'placeholder' => '- Select one -',
						'data' => $formDefaults['eqv2Hrs'],
					])
					->add('eqv2Opr', ChoiceType::class, [
						'label' => 'Operator',
						'choices' => [
							'AND' => 'AND',
							'OR' => 'OR',
						],
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'placeholder' => '- None -',
						'data' => $formDefaults['eqv2Opr'],
					])
					->add('eqv3SubjCode', ChoiceType::class, [
						'label' => 'GT Subject Code',
						'placeholder' => '- Select one -',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'choices' => $this->formOptionsService->getSubjectCodeOptions(),
						'data' => $formDefaults['eqv3SubjCode'],
					])
					->add('eqv3', ChoiceType::class, [
						'label' => 'GT Course',
						'placeholder' => '- Select one -',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'choices' => $formDefaults['eqv3Options'],
						'data' => $formDefaults['eqv3'],
					])
					->add('eqv3Hrs', ChoiceType::class, [
						'label' => 'GT Credit Hours',
						'choices' => $this->formOptionsService->getCreditHourOptions(),
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'placeholder' => '- Select one -',
						'data' => $formDefaults['eqv3Hrs'],
					])
					->add('eqv3Opr', ChoiceType::class, [
						'label' => 'Operator',
						'choices' => [
							'AND' => 'AND',
							'OR' => 'OR',
						],
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'placeholder' => '- None -',
						'data' => $formDefaults['eqv3Opr'],
					])
					->add('eqv4SubjCode', ChoiceType::class, [
						'label' => 'GT Subject Code',
						'placeholder' => '- Select one -',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'choices' => $this->formOptionsService->getSubjectCodeOptions(),
						'data' => $formDefaults['eqv4SubjCode'],
					])
					->add('eqv4', ChoiceType::class, [
						'label' => 'GT Course',
						'placeholder' => '- Select one -',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'choices' => $formDefaults['eqv4Options'],
						'data' => $formDefaults['eqv4'],
					])
					->add('eqv4Hrs', ChoiceType::class, [
						'label' => 'GT Credit Hours',
						'choices' => $this->formOptionsService->getCreditHourOptions(),
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'placeholder' => '- Select one -',
						'data' => $formDefaults['eqv4Hrs'],
					])
					->add('policy', ChoiceType::class, [
						'label' => 'Shall this become a transfer equivalency policy?',
						'choices' => [
							'Yes' => 'Yes',
							'No' => 'No',
						],
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'placeholder' => '- Select one -',
						'data' => $formDefaults['policy'],
					])
					->add('lookup', TextareaType::class, [
						'label' => 'ID Check',
						'attr' => [
							'rows' => 4
						],
						'mapped' => false,
						'data' => $formDefaults['lookup'],
						'required' => false,
						'disabled' => true,
					])
					->add('requesterType', ChoiceType::class, [
						'label' => 'Categorize the requester.',
						'choices' => [
							'Student' => 'Student',
							'Confirmed Applicant' => 'Confirmed	Applicant',
							'Accepted Applicant' => 'Accepted Applicant',
							'Applicant' => 'Applicant',
							'Graduate Student' => 'Graduate Student',
							'Graduate Applicant' => 'Graduate Applicant',
							'Employee' => 'Employee',
							'Unknown' => 'Unknown',
							'TBD' => 'TBD',
						],
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'placeholder' => '- Select one -',
						'data' => $formDefaults['requesterType'],
					])
					->add('courseInSis', ChoiceType::class, [
						'label' => 'Has the course been entered in Banner?',
						'choices' => [
							'No' => 0,
							'Yes' => 1,
						],
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'placeholder' => '- Select one -',
					])
					->add('transcriptOnHand', ChoiceType::class, [
						'label' => 'Does the Registrar\'s Office possess the transcript?',
						'choices' => [
							'No' => 0,
							'Yes' => 1,
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
					])
				;

				// Add event listener to handle dynamic population of course choices
				// based on the selected subject code
				$builder->addEventListener(
					FormEvents::PRE_SUBMIT,
					function (FormEvent $event) {
							$form = $event->getForm();
							$data = $event->getData();

							if (isset($data['eqv1SubjCode'])) {
									$courses = $this->formOptionsService->getCoursesBySubjectCode($data['eqv1SubjCode']);
									$form->add('eqv1', ChoiceType::class, [
										'label' => 'GT Course',
										'placeholder' => '- Select one -',
										'expanded' => false,
										'multiple' => false,
										'required' => false,
										'choices' => $courses,
									]);
							}

							if (isset($data['eqv2SubjCode'])) {
									$courses =
										$this->formOptionsService->getCoursesBySubjectCode($data['eqv2SubjCode']);
									$form->add('eqv2', ChoiceType::class, [
										'label' => 'GT Course',
										'placeholder' => '- Select one -',
										'expanded' => false,
										'multiple' => false,
										'required' => false,
										'choices' => $courses,
									]);
							}

							if (isset($data['eqv3SubjCode'])) {
									$courses =
										$this->formOptionsService->getCoursesBySubjectCode($data['eqv3SubjCode']);
									$form->add('eqv3', ChoiceType::class, [
										'label' => 'GT Course',
										'placeholder' => '- Select one -',
										'expanded' => false,
										'multiple' => false,
										'required' => false,
										'choices' => $courses,
									]);
							}

							if (isset($data['eqv4SubjCode'])) {
									$courses =
										$this->formOptionsService->getCoursesBySubjectCode($data['eqv4SubjCode']);
									$form->add('eqv4', ChoiceType::class, [
										'label' => 'GT Course',
										'placeholder' => '- Select one -',
										'expanded' => false,
										'multiple' => false,
										'required' => false,
										'choices' => $courses,
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