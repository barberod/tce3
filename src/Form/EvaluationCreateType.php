<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluationCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
					->add('evaluationBasics', EvaluationBasicsType::class)
					->add('evaluationInstitution', EvaluationInstitutionType::class)
					->add('evaluationCourse', EvaluationCourseType::class)
					->add('evaluationLab', EvaluationLabType::class)
					->add('evaluationAttestation', EvaluationAttestationType::class)
					->add('submit', SubmitType::class, [
						'label' => 'Submit',
						'attr' => ['class' => 'btn btn-primary btn-lg'],
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
