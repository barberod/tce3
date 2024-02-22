<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Institution;
use App\Entity\User;
use App\Service\FormOptionsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ScratchFormType extends AbstractType
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

		public function buildForm(
			FormBuilderInterface $builder,
			array $options
		): void {
				$builder
						->add('name', TextType::class, [
								'label' => 'Name',
								'constraints' => [
										new NotBlank(),
										new Length(['min' => 3, 'max' => 6]),
								],
						])
						->add('email', EmailType::class, [
								'label' => 'Email',
						])
						->add('gender', ChoiceType::class, [
								'label' => 'Gender',
								'choices' => [
										'Male' => 'male',
										'Female' => 'female',
										'Other' => 'other',
								],
								'expanded' => true,
								'multiple' => false,
						])
						->add('message', TextareaType::class, [
								'label' => 'Message',
						])
						->add('state', ChoiceType::class, [
								'choices' => $this->formOptionsService->getUsStateOptions(),
								'placeholder' => 'Select a state',
								'required' => true,
								'mapped' => false,
						])
						->add('institution', EntityType::class, [
								'class' => Institution::class,
								'placeholder' => 'Select an institution',
								'required' => true,
								'mapped' => false,
								'choices' => [], // Initially empty, will be dynamically populated
						])
						->add('department', ChoiceType::class, [
								'choices' => $this->formOptionsService->getDepartmentOptions(),
								'placeholder' => 'Select a department',
								'required' => true,
								'mapped' => false,
						])
						->add('assignee', EntityType::class, [
								'class' => User::class,
								'placeholder' => 'Select an assignee',
								'required' => true,
								'mapped' => false,
								'choices' => [], // Initially empty, will be dynamically populated
						])
						->add('subject', ChoiceType::class, [
								'choices' => $this->formOptionsService->getSubjectCodeOptions(),
								'placeholder' => 'Select a subject code',
								'required' => true,
								'mapped' => false,
						])
						->add('course', EntityType::class, [
								'class' => Course::class,
								'placeholder' => 'Select a course',
								'required' => true,
								'mapped' => false,
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
								'mapped' => false,
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
								'mapped' => false,
						])
						->add('submit', SubmitType::class, [
								'label' => 'Submit',
								'attr' => ['class' => 'btn btn-primary'],
						])
				;
				// Add event listener to handle dynamic population of university choices based on the selected state
				$builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
						$form = $event->getForm();
						$data = $event->getData();

						if (isset($data['state'])) {
								$institutions = $this->entityManager->getRepository(Institution::class)
									->findBy(['state' => $data['state']]);

								$form->add('institution', EntityType::class, [
									'class' => Institution::class,
									'placeholder' => 'Select an institution',
									'required' => true,
									'mapped' => false,
									'choices' => $institutions,
								]);
						}
				});
				// Add event listener to handle dynamic population of university choices based on the selected state
				$builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
						$form = $event->getForm();
						$data = $event->getData();

						if (isset($data['department'])) {
								$assignees = $this->entityManager
									->getRepository(User::class) // Assuming User is the entity representing faculty members
									->createQueryBuilder('u')
									->join('u.affiliations', 'a') // Assuming the property representing the affiliations in the User entity is named 'affiliations'
									->join('a.department', 'd') // Assuming the property representing the department in the Affiliation entity is named 'department'
									->where('d.id = :deptId')
									->setParameter('deptId', $data['department'])
									->orderBy('u.displayName', 'ASC')
									->getQuery()
									->getResult();

								$form->add('assignee', EntityType::class, [
									'class' => User::class,
									'placeholder' => 'Select an assignee',
									'required' => true,
									'mapped' => false,
									'choices' => $assignees,
								]);
						}
				});
		}

		public function configureOptions(OptionsResolver $resolver): void {}

}
