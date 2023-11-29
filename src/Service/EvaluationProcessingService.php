<?php

namespace App\Service;

use App\Entity\Evaluation;
use App\Entity\Institution;
use App\Entity\Note;
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
				if ($this->security->getUser() instanceof User) {
						$evaluation->setRequester($this->security->getUser());;
				} elseif ($this->security->getUser() instanceof CasUser) {
						$userAtHand = $this->entityManager->getRepository(User::class)
							->findOneBy(['username' => $this->security->getUser()->getUserIdentifier()]);
						$evaluation->setRequester($userAtHand);;
				} else {
						$evaluation->setRequester(null);
				}

				$evaluation->setStatus(1);
				$evaluation->setCreated(new \DateTime());
				$evaluation->setUpdated(new \DateTime());
				$evaluation->setSerialNum(999999);
				$evaluation->setPhase('Registrar 1');

				$evaluation->setReqAdmin($formData['requiredForAdmission']);

				if ($formData['locatedUsa'] == 'Yes' &&
					$formData['institutionListed'] == 'Yes') {
				  $institution = $this->entityManager->getRepository(Institution::class)
						->findOneBy(['id' => $formData['institution']]);
					$evaluation->setInstitution($institution);
					$evaluation->setInstitutionOther('');
					$evaluation->setInstitutionCountry('United States');
				}
				else if ($formData['locatedUsa'] == 'Yes' &&
					$formData['institutionListed'] == 'No') {
					$evaluation->setInstitutionOther($formData['institutionName']);
						$evaluation->setInstitutionCountry('United States');
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
				} else {
					$evaluation->setLabSubjCode('');
					$evaluation->setLabCrseNum('');
					$evaluation->setLabTerm('');
					$evaluation->setLabCreditBasis('');
					$evaluation->setLabCreditHrs('');
				}

				$evaluation->setAssignee(null);
				$evaluation->setDraftEquiv1Course('');
				$evaluation->setDraftEquiv1CreditHrs('');
				$evaluation->setDraftEquiv1Operator('');
				$evaluation->setDraftEquiv2Course('');
				$evaluation->setDraftEquiv2CreditHrs('');
				$evaluation->setDraftEquiv2Operator('');
				$evaluation->setDraftEquiv3Course('');
				$evaluation->setDraftEquiv3CreditHrs('');
				$evaluation->setDraftEquiv3Operator('');
				$evaluation->setDraftEquiv4Course('');
				$evaluation->setDraftEquiv4CreditHours('');
				$evaluation->setDraftPolicy('');
				$evaluation->setFinalEquiv1Course(null);
				$evaluation->setFinalEquiv1CreditHrs('');
				$evaluation->setFinalEquiv1Operator('');
				$evaluation->setFinalEquiv2Course(null);
				$evaluation->setFinalEquiv2CreditHrs('');
				$evaluation->setFinalEquiv2Operator('');
				$evaluation->setFinalEquiv3Course(null);
				$evaluation->setFinalEquiv3CreditHrs('');
				$evaluation->setFinalEquiv3Operator('');
				$evaluation->setFinalEquiv4Course(null);
				$evaluation->setFinalEquiv4CreditHrs('');
				$evaluation->setFinalPolicy('');
				$evaluation->setRequesterType('');
				$evaluation->setHoldForRequesterAdmit(0);
				$evaluation->setHoldForCourseInput(0);
				$evaluation->setHoldForTranscript(0);
				$evaluation->setTagSpotArticulated(0);
				$evaluation->setTagR1ToStudent(0);
				$evaluation->setTagDeptToStudent(0);
				$evaluation->setTagDeptToR1(0);
				$evaluation->setTagR2ToStudent(0);
				$evaluation->setTagR2ToDept(0);
				$evaluation->setTagReassigned(0);

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

				// Persist the entity
				$this->entityManager->persist($trail);
				$this->entityManager->flush(); // Save changes to the database
		}

		public function assignEvaluation(Evaluation $evaluation, array $formData): void
		{
				$assignee = $this->entityManager->getRepository(User::class)
					->findOneBy(['username' => $formData['assignee']]);
				$evaluation->setAssignee($assignee);
				$evaluation->setPhase('Department');

				// Persist the entity
				$this->entityManager->persist($evaluation);
				$this->entityManager->flush(); // Save changes to the database

				// Create a note
				if ($formData['addNote'] == 'Yes') {
						$note = new Note();
						$note->setEvaluation($evaluation);

						if ($this->security->getUser() instanceof User) {
								$note->setAuthor($this->security->getUser());
						} elseif ($this->security->getUser() instanceof CasUser) {
								$userAtHand = $this->entityManager->getRepository(User::class)
									->findOneBy(['username' => $this->security->getUser()->getUserIdentifier()]);
								$note->setAuthor($userAtHand);
						} else {
								$note->setAuthor(null);
						}

						$note->setBody($formData['noteBody']);
						$note->setCreated(new \DateTime());

						if ($formData['visibleNote'] == 'Yes') {
								$note->setVisibleToRequester(1);
						} else {
								$note->setVisibleToRequester(0);
						}

						// Persist the entity
						$this->entityManager->persist($note);
						$this->entityManager->flush(); // Save changes to the database
				}

				// Create a trail
				$trail = new Trail();
				$trail->setEvaluation($evaluation);

				$assigneeText = '';
				$assigneeText .= $assignee->attributes()['profile']['dn'];
				$assigneeText .= ' ('.$assignee->attributes()['profile']['un'].')';

				$coordinator = $this->security->getUser();
				$coordinatorText = '';
				if ($coordinator instanceof User) {
						$coordinatorText .= $this->security->getUser()->attributes()['profile']['dn'];
						$coordinatorText .= ' ('.$this->security->getUser()->attributes()['profile']['un'].')';
				} elseif ($coordinator instanceof CasUser) {
						$coordinatorText .= $this->security->getUser()->getAttributes()['profile']['dn'];
						$coordinatorText .= ' ('.$this->security->getUser()->getAttributes()['profile']['un'].')';
				} else {
						$coordinatorText .= 'Unknown';
				}

				$trail->setBody('Assigned to '.$assigneeText.' by '.$coordinatorText.'. Phase set to Department.');
				$trail->setBodyAnon('Assigned to department by coordinator.');
				$trail->setCreated(new \DateTime());

				// Persist the entity
				$this->entityManager->persist($trail);
				$this->entityManager->flush(); // Save changes to the database
		}
}