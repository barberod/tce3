<?php

namespace App\Form;

use App\Service\FormOptionsService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class EvaluationReassignType extends AbstractType
{

		private FormOptionsService $formOptionsService;

		public function __construct(
			FormOptionsService $formOptionsService
		) {
				$this->formOptionsService = $formOptionsService;
		}

		public function buildForm(
			FormBuilderInterface $builder,
			array $options
		): void {
				$builder
					->add('dept', ChoiceType::class, [
						'label' => 'Department',
						'placeholder' => '- Select one -',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'choices' => $this->formOptionsService->getDepartmentOptions(),
					])
					->add('assignee', ChoiceType::class, [
						'label' => 'Assign To',
						'placeholder' => '- Select one -',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'choices' => [], // Initially empty, will be dynamically populated
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

				// Add event listener to handle dynamic population of assignee choices
				// based on the selected department
				$builder->addEventListener(
					FormEvents::PRE_SUBMIT,
					function (FormEvent $event) {
							$form = $event->getForm();
							$data = $event->getData();

							if (isset($data['dept'])) {
									$assignees = $this->formOptionsService->getAssigneesByDepartment(
										$data['dept']
									);
									$form->add('assignee', ChoiceType::class, [
										'label' => 'Assign To',
										'placeholder' => '- Select one -',
										'expanded' => false,
										'multiple' => false,
										'required' => false,
										'choices' => $assignees,
									]);
							}
					}
				);
		}

}
