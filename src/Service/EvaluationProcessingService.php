<?php

namespace App\Service;

use App\Entity\Evaluation;
use App\Entity\Trail;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EcPhp\CasBundle\Security\Core\User\CasUser;
use Symfony\Bundle\SecurityBundle\Security;

class EvaluationProcessingService
{
		private EntityManagerInterface $entityManager;
		private Security $security;

		public function __construct(
			EntityManagerInterface $entityManager,
			Security $security
		) {
				$this->entityManager = $entityManager;
				$this->security = $security;
		}
		public function createEvaluation(array $formData): void
		{
				// Create a new instance of the Evaluation entity
				$evaluation = new Evaluation();

				// Set properties of the Evaluation entity
				$evaluation->setRequester($this->security->getUser());
				$evaluation->setStatus(1);
				$evaluation->setCreated(new \DateTime());
				$evaluation->setUpdated(new \DateTime());
				$evaluation->setSerialNum(999999);
				$evaluation->setPhase('Registrar 1');

				$evaluation->setReqAdmin($formData['evaluationBasics']['requiredForAdmission']);

				if ($formData['evaluationInstitution']['locatedUsa'] == 'Yes' &&
					$formData['institutionListed'] == 'Yes') {
					$evaluation->setInstitution($formData['institution']);
				}
				else if ($formData['locatedUsa'] == 'Yes' &&
					$formData['institutionListed'] == 'No') {
					$evaluation->setInstitutionOther($formData['institutionName']);
				}
				else if ($formData['locatedUsa'] == 'No') {
					$evaluation->setInstitutionOther($formData['institutionName']);
					$evaluation->setInstitutionCountry($formData['country']);
				}

				$evaluation->setCourseSubjCode($formData['coursePrefix']);
				$evaluation->setCourseCrseNum($formData['courseNumber']);
				$evaluation->setCourseTerm($formData['courseSemester']);
				$evaluation->setCourseCreditBasis($formData['courseCreditBasis']);
				$evaluation->setCourseCreditHrs($formData['courseCreditHours']);

				if ($formData['hasLab'] == 'Yes') {
					$evaluation->setLabSubjCode($formData['labPrefix']);
					$evaluation->setLabCrseNum($formData['labNumber']);
					$evaluation->setLabTerm($formData['labSemester']);
					$evaluation->setLabCreditBasis($formData['labCreditBasis']);
					$evaluation->setLabCreditHrs($formData['labCreditHours']);
				}

				// Persist the entity
				$this->entityManager->persist($evaluation);
				$this->entityManager->flush(); // Save changes to the database

				// Create a new instance of the Trail entity
				$trail = new Trail();

				// Set properties of the Trail entity
				$trail->setEvaluation($evaluation);

				$requester = $this->security->getUser();
				$requesterText = '';
				if ($requester instanceof User) {
						$requesterText .= $this->security->getUser()->attributes()['profile']['dn'];
						$requesterText .= ' ('.$this->security->getUser()->attributes()['profile']['org_id'].')';
				} elseif ($requester instanceof CasUser) {
						$requesterText .= $this->security->getUser()->getAttributes()['profile']['dn'];
						$requesterText .= ' ('.$this->security->getUser()->getAttributes()['profile']['org_id'].')';
				} else {
						$requesterText .= 'Unknown';
				}

				$trail->setBody('Evaluation initiated by '.$requesterText.'. Phase set to Registrar 1.');
				$trail->setBodyAnon('Evaluation initiated by requester.');
				$trail->setCreated(new \DateTime());
		}
}