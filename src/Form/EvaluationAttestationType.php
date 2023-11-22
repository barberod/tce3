<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluationAttestationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
				$builder
					->add('attest', ChoiceType::class, [
						'label' => 'Attestation',
						'choices' => [
							'Yes' => 'Yes',
						],
						'expanded' => false,
						'multiple' => false,
						'required' => true,
						'mapped' => false,
						'placeholder' => '---',
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
