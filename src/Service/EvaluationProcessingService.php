<?php

namespace App\Service;

use App\Entity\Course;
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

		/**
		 * Create
		 *
		 * @param array $formData
		 */
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

		/**
		 * Update
		 */

		/**
		 * Delete
		 */

		/**
		 * Annotate
		 */
		public function annotateEvaluation(Evaluation $evaluation, array $formData): void
		{
				// Create a note
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

		/**
		 * Annotate-as-Requester
		 *
		 * @param Evaluation $evaluation
		 * @param array $formData
		 */
		public function annotateAsRequesterEvaluation(Evaluation $evaluation, array
		$formData): void
		{
				// Create a note
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
				$note->setVisibleToRequester(1);

				// Persist the entity
				$this->entityManager->persist($note);
				$this->entityManager->flush(); // Save changes to the database
		}

		/**
		 * Append
		 */

		/**
		 * Assign
		 *
		 * @param Evaluation $evaluation
		 * @param array $formData
		 */
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

		/**
		 * Evaluate
		 */

		/**
		 * Example
		 */

		/**
		 * Finalize
		 */

		/**
		 * Forward
		 */

		/**
		 * From-complete-to-hold
		 */

		/**
		 * From-dept-to-r1
		 */

		/**
		 * From-dept-to-student
		 */

		/**
		 * From-r1-to-student
		 */

		/**
		 * From-r2-to-dept
		 */

		/**
		 * From-r2-to-student
		 */

		/**
		 * From-student-to-r1
		 */

		/**
		 * Hide
		 */

		/**
		 * Pass
		 */

		/**
		 * Reassign
		 */

		/**
		 * Resubmit
		 */

		/**
		 * Spot-articulate
		 */
		public function spotArticulateEvaluation(Evaluation $evaluation, array $formData): void
		{
				$draftEqvText = ' Draft equiv:';
				if ($formData['eqvCnt'] == 0) {
						$draftEqvText .= 'No equivalencies ';
				}

				if (in_array($formData['eqvCnt'], array(1, 2, 3, 4))) {
						$eqv1 = $this->entityManager->getRepository(Course::class)
							->findOneBy(['id' => $formData['eqv1']]);
						$evaluation->setDraftEquiv1Course($eqv1->getSubjectCode().' '.$eqv1->getCourseNumber());
						$evaluation->setDraftEquiv1CreditHrs($formData['eqv1Hrs']);
						$draftEqvText .= $eqv1->getSubjectCode().' '
							.$eqv1->getCourseNumber().' ('.$formData['eqv1Hrs'].' hrs) ';
				}

				if (in_array($formData['eqvCnt'], array(2, 3, 4))) {
						$evaluation->setDraftEquiv1Operator($formData['eqv1Opr']);
						$eqv2 = $this->entityManager->getRepository(Course::class)
							->findOneBy(['id' => $formData['eqv2']]);
						$evaluation->setDraftEquiv2Course($eqv2->getSubjectCode().' '.$eqv2->getCourseNumber());
						$evaluation->setDraftEquiv2CreditHrs($formData['eqv2Hrs']);

						if (in_array($formData['eqv1Opr'], array('AND', 'OR'))) {
								$draftEqvText .= $formData['eqv1Opr'].' ';
						} else {
								$draftEqvText .= ', ';
						}
						$draftEqvText .= $eqv2->getSubjectCode().' '
							.$eqv2->getCourseNumber().' ('.$formData['eqv2Hrs'].' hrs) ';
				}

				if (in_array($formData['eqvCnt'], array(3, 4))) {
						$evaluation->setDraftEquiv2Operator($formData['eqv2Opr']);
						$eqv3 = $this->entityManager->getRepository(Course::class)
							->findOneBy(['id' => $formData['eqv3']]);
						$evaluation->setDraftEquiv3Course($eqv3->getSubjectCode().' '.$eqv3->getCourseNumber());
						$evaluation->setDraftEquiv3CreditHrs($formData['eqv3Hrs']);

						if (in_array($formData['eqv2Opr'], array('AND', 'OR'))) {
								$draftEqvText .= $formData['eqv2Opr'].' ';
						} else {
								$draftEqvText .= ', ';
						}
						$draftEqvText .= $eqv3->getSubjectCode().' '
							.$eqv3->getCourseNumber().' ('.$formData['eqv3Hrs'].' hrs) ';
				}

				if ($formData['eqvCnt'] == 4) {
						$evaluation->setDraftEquiv3Operator($formData['eqv3Opr']);
						$eqv4 = $this->entityManager->getRepository(Course::class)
							->findOneBy(['id' => $formData['eqv4']]);
						$evaluation->setDraftEquiv4Course($eqv4->getSubjectCode().' '.$eqv4->getCourseNumber());
						$evaluation->setDraftEquiv4CreditHours($formData['eqv4Hrs']);

						if (in_array($formData['eqv3Opr'], array('AND', 'OR'))) {
								$draftEqvText .= $formData['eqv3Opr'].' ';
						} else {
								$draftEqvText .= ', ';
						}
						$draftEqvText .= $eqv4->getSubjectCode().' '
							.$eqv4->getCourseNumber().' ('.$formData['eqv4Hrs'].' hrs) ';
				}

				$draftEqvText = substr($draftEqvText, 0, -1);

				$evaluation->setDraftPolicy($formData['policy']);
				$policyText = '';
				if ($formData['policy'] == 'Yes') {
						$policyText .= ' Policy.';
				} elseif ($formData['policy'] == 'No') {
						$policyText .= ' Not policy.';
				}

				$evaluation->setPhase('Registrar 2');
				$evaluation->setTagSpotArticulated(1);

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

				$trail->setBody('Spot articulation by '.$coordinatorText.$draftEqvText.$policyText.' Phase set to Registrar 2.');
				$trail->setBodyAnon('Initial review by coordinator. Phase set to Registrar 2.');
				$trail->setCreated(new \DateTime());

				// Persist the entity
				$this->entityManager->persist($trail);
				$this->entityManager->flush(); // Save changes to the database
		}
}