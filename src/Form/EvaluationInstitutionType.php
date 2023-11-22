<?php

namespace App\Form;

use App\Entity\Institution;
use App\Service\FormOptionsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EvaluationInstitutionType extends AbstractType
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
        $builder
					->add('locatedUsa', ChoiceType::class, [
						'label' => 'Is the institution located in the United States?',
						'choices' => [
							'No' => 'No',
							'Yes' => 'Yes',
						],
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'mapped' => false,
						'placeholder' => '- Select one -',
						'data' => 'Yes',
					])
					->add('state', ChoiceType::class, [
						'choices' => $this->formOptionsService->getUsStateOptions(),
						'placeholder' => 'Select a state',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'mapped' => false,
					])
					->add('institution', ChoiceType::class, [
						'label' => 'Institution',
						'placeholder' => 'Select an institution',
						'expanded' => false,
						'multiple' => false,
						'required' => false,
						'mapped' => false,
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
						'mapped' => false,
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
						'mapped' => false,
					])
					->add('institutionName', TextType::class, [
						'label' => 'Name of Institution',
						'required' => false,
						'mapped' => false,
						'help' => 'Example: "Oxford University"',
					])
        ;
				// Add event listener to handle dynamic population of university choices based on the selected state
				$builder->addEventListener(
					FormEvents::PRE_SUBMIT,
					function (FormEvent $event) {
							$form = $event->getForm();
							$data = $event->getData();

							if (isset($data['state'])) {
									$institutions = $this->entityManager->getRepository(Institution::class)
										->findBy(['state' => $data['state']]);
									$form->add('institution', ChoiceType::class, [
										'label' => 'Institution',
										'placeholder' => 'Select an institution',
										'expanded' => false,
										'multiple' => false,
										'required' => false,
										'mapped' => false,
										'choices' => $institutions,
									]);
							}
					}
				);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
