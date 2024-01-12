<?php

namespace App\Form;

use App\Service\FormOptionsService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluationSpotArticulateType extends AbstractType
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
					])
					->add('eqv1SubjCode', ChoiceType::class, [
						'label' => 'GT Subject Code',
						'placeholder' => '- Select one -',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'choices' => $this->formOptionsService->getSubjectCodeOptions(),
					])
					->add('eqv1', ChoiceType::class, [
						'label' => 'GT Course',
						'placeholder' => '- Select one -',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'choices' => [], // Initially empty, will be dynamically populated
					])
					->add('eqv1Hrs', ChoiceType::class, [
						'label' => 'GT Credit Hours',
						'choices' => $this->formOptionsService->getCreditHourOptions(),
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'placeholder' => '- Select one -',
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
					])
					->add('eqv2SubjCode', ChoiceType::class, [
						'label' => 'GT Subject Code',
						'placeholder' => '- Select one -',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'choices' => $this->formOptionsService->getSubjectCodeOptions(),
					])
					->add('eqv2', ChoiceType::class, [
						'label' => 'GT Course',
						'placeholder' => '- Select one -',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'choices' => [], // Initially empty, will be dynamically populated
					])
					->add('eqv2Hrs', ChoiceType::class, [
						'label' => 'GT Credit Hours',
						'choices' => $this->formOptionsService->getCreditHourOptions(),
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'placeholder' => '- Select one -',
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
					])
					->add('eqv3SubjCode', ChoiceType::class, [
						'label' => 'GT Subject Code',
						'placeholder' => '- Select one -',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'choices' => $this->formOptionsService->getSubjectCodeOptions(),
					])
					->add('eqv3', ChoiceType::class, [
						'label' => 'GT Course',
						'placeholder' => '- Select one -',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'choices' => [], // Initially empty, will be dynamically populated
					])
					->add('eqv3Hrs', ChoiceType::class, [
						'label' => 'GT Credit Hours',
						'choices' => $this->formOptionsService->getCreditHourOptions(),
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'placeholder' => '- Select one -',
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
					])
					->add('eqv4SubjCode', ChoiceType::class, [
						'label' => 'GT Subject Code',
						'placeholder' => '- Select one -',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'choices' => $this->formOptionsService->getSubjectCodeOptions(),
					])
					->add('eqv4', ChoiceType::class, [
						'label' => 'GT Course',
						'placeholder' => '- Select one -',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'choices' => [], // Initially empty, will be dynamically populated
					])
					->add('eqv4Hrs', ChoiceType::class, [
						'label' => 'GT Credit Hours',
						'choices' => $this->formOptionsService->getCreditHourOptions(),
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'placeholder' => '- Select one -',
					])
					->add('policy', ChoiceType::class, [
						'label' => 'Shall this become a transfer equivalency policy?',
						'choices' => [
							'Policy' => 'Policy',
							'Not Policy' => 'Not Policy',
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
}
