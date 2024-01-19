<?php

namespace App\Service;

use App\Entity\Course;
use App\Entity\Evaluation;
use App\Entity\Institution;
use App\Entity\Note;
use App\Entity\Trail;
use App\Entity\User;
use App\Service\EmailService;
use App\Service\EvaluationFilesService;
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
				$userAtHand = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $this->security->getUser()->getUserIdentifier()]);
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
				$institution = $this->entityManager->getRepository(Institution::class)->findOneBy(['id' => $formData['institution']]);
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
			$evaluation->setCourseTitle($formData['courseTitle']);
			$evaluation->setCourseTerm($formData['courseSemester']);
			$evaluation->setCourseCreditBasis($formData['courseCreditBasis']);
			$evaluation->setCourseCreditHrs($formData['courseCreditHours']);

			if ($formData['hasLab'] == 'Yes') {
				$evaluation->setLabSubjCode($formData['labPrefix']);
				$evaluation->setLabCrseNum($formData['labNumber']);
				$evaluation->setLabTitle($formData['labTitle']);
				$evaluation->setLabTerm($formData['labSemester']);
				$evaluation->setLabCreditBasis($formData['labCreditBasis']);
				$evaluation->setLabCreditHrs($formData['labCreditHours']);
			} else {
				$evaluation->setLabSubjCode('');
				$evaluation->setLabCrseNum('');
				$evaluation->setLabTitle('');
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

			// Store the syllabus file
			if ((isset($formData['courseSyllabus'])) && (!empty($formData['courseSyllabus']))) {
				$filesService = new EvaluationFilesService();
				$filesService->saveSyllabus($evaluation, $formData);
			}

			// Store the document file
			if ((isset($formData['courseDocument'])) && (!empty($formData['courseDocument']))) {
				$filesService = new EvaluationFilesService();
				$filesService->saveCourseDocument($evaluation, $formData);
			}

			// Store the lab syllabus file
			if ((isset($formData['labSyllabus'])) && (!empty($formData['labSyllabus']))) {
				$filesService = new EvaluationFilesService();
				$filesService->saveLabSyllabus($evaluation, $formData);
			}

			// Store the lab document file
			if ((isset($formData['labDocument'])) && (!empty($formData['labDocument']))) {
				$filesService = new EvaluationFilesService();
				$filesService->saveLabDocument($evaluation, $formData);
			}

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

			// Send email
			$emailService = new EmailService($this->entityManager);
			$emailService->emailToRequesterUponNewRequest($evaluation->getRequester()->getUsername(), $evaluation);
		}

		/**
		 * Update
		 * 
		 * @param Evaluation $evaluation
		 * @param array $formData
		 */
		public function updateEvaluation(Evaluation $evaluation, array $formData): void
		{
			$evaluation->setUpdated(new \DateTime());
			$evaluation->setReqAdmin($formData['requiredForAdmission']);

			if ($formData['locatedUsa'] == 'Yes' && 
				$formData['institutionListed'] == 'Yes') {
				$institution = $this->entityManager->getRepository(Institution::class)->findOneBy(['id' => $formData['institution']]);
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

			$evaluation->setCourseSubjCode($formData['courseSubjCode']);
			$evaluation->setCourseCrseNum($formData['courseCrseNum']);
			$evaluation->setCourseTitle($formData['courseTitle']);
			$evaluation->setCourseTerm($formData['courseTerm']);
			$evaluation->setCourseCreditBasis($formData['courseCreditBasis']);
			$evaluation->setCourseCreditHrs($formData['courseCreditHours']);

			if ($formData['hasLab'] == 'Yes') {
				$evaluation->setLabSubjCode($formData['labPrefix']);
				$evaluation->setLabCrseNum($formData['labNumber']);
				$evaluation->setLabTitle($formData['labTitle']);
				$evaluation->setLabTerm($formData['labSemester']);
				$evaluation->setLabCreditBasis($formData['labCreditBasis']);
				$evaluation->setLabCreditHrs($formData['labCreditHours']);
			} else {
				$evaluation->setLabSubjCode('');
				$evaluation->setLabCrseNum('');
				$evaluation->setLabTitle('');
				$evaluation->setLabTerm('');
				$evaluation->setLabCreditBasis('');
				$evaluation->setLabCreditHrs('');
			}

			// Persist the entity
			$this->entityManager->persist($evaluation);
			$this->entityManager->flush(); // Save changes to the database

			// Create a new instance of the Trail entity
			$trail = new Trail();

			// Set properties of the Trail entity
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

			$trail->setBody('Evaluation details edited by '.$coordinatorText.'.');
			$trail->setBodyAnon('Evaluation details edited by coordinator.');
			$trail->setCreated(new \DateTime());

			// Persist the entity
			$this->entityManager->persist($trail);
			$this->entityManager->flush(); // Save changes to the database
		}

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

			// Indicate the evaluation was updated
			$evaluation->setUpdated(new \DateTime());
			$this->entityManager->persist($evaluation);
			$this->entityManager->flush(); // Save changes to the database
		}

		/**
		 * Update
		 * 
		 * @param Evaluation $evaluation
		 * @param array $formData
		 */
		public function updateAsRequesterEvaluation(Evaluation $evaluation, array $formData): void
		{
			$evaluation->setUpdated(new \DateTime());
			$evaluation->setReqAdmin($formData['requiredForAdmission']);

			if ($formData['locatedUsa'] == 'Yes' && 
				$formData['institutionListed'] == 'Yes') {
				$institution = $this->entityManager->getRepository(Institution::class)->findOneBy(['id' => $formData['institution']]);
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

			$evaluation->setCourseSubjCode($formData['courseSubjCode']);
			$evaluation->setCourseCrseNum($formData['courseCrseNum']);
			$evaluation->setCourseTitle($formData['courseTitle']);
			$evaluation->setCourseTerm($formData['courseTerm']);
			$evaluation->setCourseCreditBasis($formData['courseCreditBasis']);
			$evaluation->setCourseCreditHrs($formData['courseCreditHours']);

			if ($formData['hasLab'] == 'Yes') {
				$evaluation->setLabSubjCode($formData['labPrefix']);
				$evaluation->setLabCrseNum($formData['labNumber']);
				$evaluation->setLabTitle($formData['labTitle']);
				$evaluation->setLabTerm($formData['labSemester']);
				$evaluation->setLabCreditBasis($formData['labCreditBasis']);
				$evaluation->setLabCreditHrs($formData['labCreditHours']);
			} else {
				$evaluation->setLabSubjCode('');
				$evaluation->setLabCrseNum('');
				$evaluation->setLabTitle('');
				$evaluation->setLabTerm('');
				$evaluation->setLabCreditBasis('');
				$evaluation->setLabCreditHrs('');
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

			$trail->setBody('Evaluation details edited by '.$requesterText.'.');
			$trail->setBodyAnon('Evaluation details edited by requester.');
			$trail->setCreated(new \DateTime());

			// Persist the entity
			$this->entityManager->persist($trail);
			$this->entityManager->flush(); // Save changes to the database
		}

		/**
		 * Annotate as Requester
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

			// Indicate the evaluation was updated
			$evaluation->setUpdated(new \DateTime());
			$this->entityManager->persist($evaluation);
			$this->entityManager->flush(); // Save changes to the database
		}

		/**
		 * Annotation Deletion
		 */
		public function annotationDelete(Note $note): void
		{
			$this->entityManager->remove($note);
			$this->entityManager->flush();
		}

		/**
		 * Append
		 * 
		 * @param Evaluation $evaluation
		 * @param array $formData
		 */
		public function appendEvaluation(Evaluation $evaluation, array $formData): void
		{
			// Store the attached file
			if ((isset($formData['attachedFile'])) && (!empty($formData['attachedFile']))) {
				$filesService = new EvaluationFilesService();
				$filesService->saveAttachment($evaluation, $formData);
			}

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

			$trail->setBody('Supplemental file attached to evaluation by '.$coordinatorText.'.');
			$trail->setBodyAnon('Supplemental file attached to evaluation by coordinator.');
			$trail->setCreated(new \DateTime());

			// Persist the entity
			$this->entityManager->persist($trail);
			$this->entityManager->flush(); // Save changes to the database			
		}

		/**
		 * Append as Requester
		 * 
		 * @param Evaluation $evaluation
		 * @param array $formData
		 */
		public function appendAsRequesterEvaluation(Evaluation $evaluation, array $formData): void
		{
			// Store the attached file
			if ((isset($formData['attachedFile'])) && (!empty($formData['attachedFile']))) {
				$filesService = new EvaluationFilesService();
				$filesService->saveAttachment($evaluation, $formData);
			}

			// Create a trail
			$trail = new Trail();
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

			$trail->setBody('Supplemental file attached to evaluation by '.$requesterText.'.');
			$trail->setBodyAnon('Supplemental file attached to evaluation by requester.');
			$trail->setCreated(new \DateTime());

			// Persist the entity
			$this->entityManager->persist($trail);
			$this->entityManager->flush(); // Save changes to the database			
		}
		
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
				$evaluation->setUpdated(new \DateTime());

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
				$assigneeText .= $assignee->getDisplayName();
				$assigneeText .= ' ('.$assignee->getUsername().')';

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

				// Send email
				$emailService = new EmailService($this->entityManager);
				$emailService->emailToAssigneeUponNewAssignment($assignee->getUsername(), $evaluation);
		}

		/**
		 * Evaluate
		 *
		 * @param Evaluation $evaluation
		 * @param array $formData
		 */
		public function evaluateEvaluation(Evaluation $evaluation, array $formData): void
		{
				$draftEqvText = ' Draft equiv: ';
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
				$draftEqvText .= '.';

				$evaluation->setDraftPolicy($formData['policy']);
				$policyText = '';
				if ($formData['policy'] == 'Yes') {
						$policyText .= ' Policy.';
				} elseif ($formData['policy'] == 'No') {
						$policyText .= ' Not policy.';
				}

				$evaluation->setPhase('Registrar 2');
				$evaluation->setUpdated(new \DateTime());

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

				$assignee = $this->security->getUser();
				$assigneeText = '';
				if ($assignee instanceof User) {
						$assigneeText .= $this->security->getUser()->attributes()['profile']['dn'];
						$assigneeText .= ' ('.$this->security->getUser()->attributes()['profile']['un'].')';
				} elseif ($assignee instanceof CasUser) {
						$assigneeText .= $this->security->getUser()->getAttributes()['profile']['dn'];
						$assigneeText .= ' ('.$this->security->getUser()->getAttributes()['profile']['un'].')';
				} else {
						$assigneeText .= 'Unknown';
				}

				$trail->setBody('Draft equivalencies entered by '.$assigneeText.$draftEqvText.$policyText.' Phase set to Registrar 2.');
				$trail->setBodyAnon('Initial review by department. Phase set to Registrar 2.');
				$trail->setCreated(new \DateTime());

				// Persist the entity
				$this->entityManager->persist($trail);
				$this->entityManager->flush(); // Save changes to the database
		}

		/**
		 * Example
		 */

		/**
		 * Finalize
		 */
		public function finalizeEvaluation(Evaluation $evaluation, array $formData): void
		{
				$finalEqvText = ' Final equiv: ';
				if ($formData['eqvCnt'] == 0) {
						$finalEqvText .= 'No equivalencies ';
				}

				if (in_array($formData['eqvCnt'], array(1, 2, 3, 4))) {
						$eqv1 = $this->entityManager->getRepository(Course::class)->findOneBy(['id' => $formData['eqv1']]);
						$evaluation->setFinalEquiv1Course($eqv1);
						$evaluation->setFinalEquiv1CreditHrs($formData['eqv1Hrs']);
						$finalEqvText .= $eqv1->getSubjectCode().' '.$eqv1->getCourseNumber().' ('.$formData['eqv1Hrs'].' hrs) ';
				}

				if (in_array($formData['eqvCnt'], array(2, 3, 4))) {
						$evaluation->setFinalEquiv1Operator($formData['eqv1Opr']);
						$eqv2 = $this->entityManager->getRepository(Course::class)->findOneBy(['id' => $formData['eqv2']]);
						$evaluation->setFinalEquiv2Course($eqv2);
						$evaluation->setFinalEquiv2CreditHrs($formData['eqv2Hrs']);

						if (in_array($formData['eqv1Opr'], array('AND', 'OR'))) {
								$finalEqvText .= $formData['eqv1Opr'].' ';
						} else {
								$finalEqvText .= ', ';
						}
						$finalEqvText .= $eqv2->getSubjectCode().' '.$eqv2->getCourseNumber().' ('.$formData['eqv2Hrs'].' hrs) ';
				}

				if (in_array($formData['eqvCnt'], array(3, 4))) {
						$evaluation->setFinalEquiv2Operator($formData['eqv2Opr']);
						$eqv3 = $this->entityManager->getRepository(Course::class)->findOneBy(['id' => $formData['eqv3']]);
						$evaluation->setFinalEquiv3Course($eqv3);
						$evaluation->setFinalEquiv3CreditHrs($formData['eqv3Hrs']);

						if (in_array($formData['eqv2Opr'], array('AND', 'OR'))) {
								$finalEqvText .= $formData['eqv2Opr'].' ';
						} else {
								$finalEqvText .= ', ';
						}
						$finalEqvText .= $eqv3->getSubjectCode().' '.$eqv3->getCourseNumber().' ('.$formData['eqv3Hrs'].' hrs) ';
				}

				if ($formData['eqvCnt'] == 4) {
						$evaluation->setFinalEquiv3Operator($formData['eqv3Opr']);
						$eqv4 = $this->entityManager->getRepository(Course::class)->findOneBy(['id' => $formData['eqv4']]);
						$evaluation->setFinalEquiv4Course($eqv4);
						$evaluation->setFinalEquiv4CreditHrs($formData['eqv4Hrs']);

						if (in_array($formData['eqv3Opr'], array('AND', 'OR'))) {
								$finalEqvText .= $formData['eqv3Opr'].' ';
						} else {
								$finalEqvText .= ', ';
						}
						$finalEqvText .= $eqv4->getSubjectCode().' '.$eqv4->getCourseNumber().' ('.$formData['eqv4Hrs'].' hrs) ';
				}

				$finalEqvText = substr($finalEqvText, 0, -1);
				$finalEqvText .= '.';

				$evaluation->setFinalPolicy($formData['policy']);
				$policyText = '';
				if ($formData['policy'] == 'Yes') {
						$policyText .= ' Policy.';
				} elseif ($formData['policy'] == 'No') {
						$policyText .= ' Not policy.';
				}

				$evaluation->setRequesterType($formData['requesterType']);
				$evaluation->setCourseInSis($formData['courseInSis']);
				$evaluation->setTranscriptOnHand($formData['transcriptOnHand']);

				$evaluation->setPhase('Complete');
				$evaluation->setUpdated(new \DateTime());

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

				$trail->setBody('Final equivalencies entered by '.$coordinatorText.$finalEqvText.$policyText.' Phase set to Complete.');
				$trail->setBodyAnon('Finalized by Registrar\'s Office. Phase set to Complete.');
				$trail->setCreated(new \DateTime());

				// Persist the entity
				$this->entityManager->persist($trail);
				$this->entityManager->flush(); // Save changes to the database

				// Send email
				$emailService = new EmailService($this->entityManager);
				$emailService->emailToRequesterUponCompletion($evaluation->getRequester()->getUsername(), $evaluation);
		}

		/**
		 * Forward
		 *
		 * @param Evaluation $evaluation
		 * @param array $formData
		 */
		public function forwardEvaluation(Evaluation $evaluation, array $formData): void
		{
				$assignee = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $formData['assignee']]);
				$evaluation->setAssignee($assignee);
				$evaluation->setPhase('Department');
				$evaluation->setUpdated(new \DateTime());
				$evaluation->setTagReassigned(1);

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
				$assigneeText .= $assignee->getDisplayName();
				$assigneeText .= ' ('.$assignee->getUsername().')';

				$forwarder = $this->security->getUser();
				$forwarderText = '';
				if ($forwarder instanceof User) {
						$forwarderText .= $this->security->getUser()->attributes()['profile']['dn'];
						$forwarderText .= ' ('.$this->security->getUser()->attributes()['profile']['un'].')';
				} elseif ($forwarder instanceof CasUser) {
						$forwarderText .= $this->security->getUser()->getAttributes()['profile']['dn'];
						$forwarderText .= ' ('.$this->security->getUser()->getAttributes()['profile']['un'].')';
				} else {
						$forwarderText .= 'Unknown';
				}

				$trail->setBody('Forwarded to '.$assigneeText.' by '.$forwarderText.'. Phase is Department.');
				$trail->setBodyAnon('Forwarded to departmental colleague or different department.');
				$trail->setCreated(new \DateTime());

				// Persist the entity
				$this->entityManager->persist($trail);
				$this->entityManager->flush(); // Save changes to the database

				// Send email
				$emailService = new EmailService($this->entityManager);
				$emailService->emailToAssigneeUponNewAssignment($assignee->getUsername(), $evaluation);
		}

		/**
		 * From-complete-to-hold
		 *
		 * @param Evaluation $evaluation
		 * @param array $formData
		 */
		public function fromCompleteToHoldEvaluation(Evaluation $evaluation, array $formData): void
		{
				if ($formData['holdForRequesterAdmit'] == 'Yes') {
						$evaluation->setHoldForRequesterAdmit(1);
				} else {
						$evaluation->setHoldForRequesterAdmit(0);
				}

				if ($formData['holdForCourseInput'] == 'Yes') {
						$evaluation->setHoldForCourseInput(1);
				} else {
						$evaluation->setHoldForCourseInput(0);
				}

				if ($formData['holdForTranscript'] == 'Yes') {
						$evaluation->setHoldForTranscript(1);
				} else {
						$evaluation->setHoldForTranscript(0);
				}

				$evaluation->setPhase('Hold');
				$evaluation->setUpdated(new \DateTime());

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

				$holdText = '';
				if ($formData['holdForRequesterAdmit'] == "Yes") {
						$holdText .= 'Hold for requester admission.';
				}
				if ($formData['holdForCourseInput'] == "Yes") {
						$holdText .= 'Hold for course input.';
				}
				if ($formData['holdForTranscript'] == "Yes") {
						$holdText .= 'Hold for transcript.';
				}

				$trail->setBody('Put on hold by '.$coordinatorText.'. '.$holdText.' Phase set to Hold.');
				$trail->setBodyAnon('Put on hold by coordinator.');
				$trail->setCreated(new \DateTime());

				// Persist the entity
				$this->entityManager->persist($trail);
				$this->entityManager->flush(); // Save changes to the database
		}

		/**
		 * From-dept-to-r1
		 */
		public function fromDeptToR1Evaluation(Evaluation $evaluation, array $formData): void
		{
				$evaluation->setPhase('Registrar 1');
				$evaluation->setUpdated(new \DateTime());
				$evaluation->setTagDeptToR1(1);

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

				$trail->setBody('Sent back to Registrar by '.$coordinatorText.'. Phase set to Registrar 1.');
				$trail->setBodyAnon('Sent back to Registrar by department.');
				$trail->setCreated(new \DateTime());

				// Persist the entity
				$this->entityManager->persist($trail);
				$this->entityManager->flush(); // Save changes to the database
		}

		/**
		 * From-dept-to-student
		 */
		public function fromDeptToStudentEvaluation(Evaluation $evaluation, array
		$formData): void
		{
				$evaluation->setPhase('Student');
				$evaluation->setUpdated(new \DateTime());
				$evaluation->setTagDeptToStudent(1);

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

				$trail->setBody('Sent back to Student by '.$coordinatorText.'. Phase set to Student. Must be resubmitted.');
				$trail->setBodyAnon('Sent back to student. Must be resubmitted.');
				$trail->setCreated(new \DateTime());

				// Persist the entity
				$this->entityManager->persist($trail);
				$this->entityManager->flush(); // Save changes to the database

				// Send email
				$emailService = new EmailService($this->entityManager);
				$emailService->emailToRequesterUponSendBackToRequester($evaluation->getRequester()->getUsername(), $evaluation);
		}

		/**
		 * From-r1-to-student
		 *
		 * @param Evaluation $evaluation
		 * @param array $formData
		 */
		public function fromR1ToStudentEvaluation(Evaluation $evaluation, array
		$formData): void
		{
				$evaluation->setPhase('Student');
				$evaluation->setUpdated(new \DateTime());
				$evaluation->setTagR1ToStudent(1);

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

				$trail->setBody('Sent back to Student by '.$coordinatorText.'. Phase set to Student. Must be resubmitted.');
				$trail->setBodyAnon('Sent back to student by Registrar. Must be resubmitted.');
				$trail->setCreated(new \DateTime());

				// Persist the entity
				$this->entityManager->persist($trail);
				$this->entityManager->flush(); // Save changes to the database

				// Send email
				$emailService = new EmailService($this->entityManager);
				$emailService->emailToRequesterUponSendBackToRequester($evaluation->getRequester()->getUsername(), $evaluation);
		}

		/**
		 * From-r2-to-dept
		 *
		 * @param Evaluation $evaluation
		 * @param array $formData
		 */
		public function fromR2ToDeptEvaluation(Evaluation $evaluation, array $formData): void
		{
				$assignee = $this->entityManager->getRepository(User::class)
					->findOneBy(['username' => $formData['assignee']]);
				$evaluation->setAssignee($assignee);
				$evaluation->setPhase('Department');
				$evaluation->setUpdated(new \DateTime());
				$evaluation->setTagR2ToDept(1);

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

				$trail->setBody('Sent back to Department by '.$coordinatorText.'. Assigned to '
					.$assigneeText.'. Phase set to Department.');
				$trail->setBodyAnon('Sent back to department by coordinator.');
				$trail->setCreated(new \DateTime());

				// Persist the entity
				$this->entityManager->persist($trail);
				$this->entityManager->flush(); // Save changes to the database
		}

		/**
		 * From-r2-to-student
		 *
		 * @param Evaluation $evaluation
		 * @param array $formData
		 */
		public function fromR2ToStudentEvaluation(Evaluation $evaluation, array
		$formData): void
		{
				$evaluation->setPhase('Student');
				$evaluation->setUpdated(new \DateTime());
				$evaluation->setTagR2ToStudent(1);

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

				$trail->setBody('Sent back to Student by '.$coordinatorText.'. Phase set to Student. Must be resubmitted.');
				$trail->setBodyAnon('Sent back to student by Registrar. Must be resubmitted.');
				$trail->setCreated(new \DateTime());

				// Persist the entity
				$this->entityManager->persist($trail);
				$this->entityManager->flush(); // Save changes to the database

				// Send email
				$emailService = new EmailService($this->entityManager);
				$emailService->emailToRequesterUponSendBackToRequester($evaluation->getRequester()->getUsername(), $evaluation);
		}

		/**
		 * From-student-to-r1
		 *
		 * @param Evaluation $evaluation
		 * @param array $formData
		 */
		public function fromStudentToR1Evaluation(Evaluation $evaluation, array
		$formData): void
		{
				$evaluation->setPhase('Registrar 1');
				$evaluation->setUpdated(new \DateTime());

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

				$trail->setBody('Advanced to Registrar 1 by '.$coordinatorText.'. Phase set to Registrar 1.');
				$trail->setBodyAnon('Advanced to Registrar by coordinator.');
				$trail->setCreated(new \DateTime());

				// Persist the entity
				$this->entityManager->persist($trail);
				$this->entityManager->flush(); // Save changes to the database
		}

		/**
		 * Hide
		 *
		 * @param Evaluation $evaluation
		 * @param array $formData
		 */
		public function hideEvaluation(Evaluation $evaluation, array $formData): void
		{
				if ($formData['hideYesOrNo'] !== 'Yes') {
						return;
				}

				$evaluation->setStatus(0);
				$evaluation->setUpdated(new \DateTime());

				// Persist the entity
				$this->entityManager->persist($evaluation);
				$this->entityManager->flush(); // Save changes to the database

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

				$trail->setBody('Hidden by '.$coordinatorText.'.');
				$trail->setBodyAnon('Hidden by coordinator.');
				$trail->setCreated(new \DateTime());

				// Persist the entity
				$this->entityManager->persist($trail);
				$this->entityManager->flush(); // Save changes to the database
		}

		/**
		 * Hold
		 *
		 * @param Evaluation $evaluation
		 * @param array $formData
		 */
		public function holdEvaluation(Evaluation $evaluation, array $formData): void
		{
				if ($formData['holdForRequesterAdmit'] == 'Yes') {
						$evaluation->setHoldForRequesterAdmit(1);
				} else {
						$evaluation->setHoldForRequesterAdmit(0);
				}

				if ($formData['holdForCourseInput'] == 'Yes') {
						$evaluation->setHoldForCourseInput(1);
				} else {
						$evaluation->setHoldForCourseInput(0);
				}

				if ($formData['holdForTranscript'] == 'Yes') {
						$evaluation->setHoldForTranscript(1);
				} else {
						$evaluation->setHoldForTranscript(0);
				}

				$evaluation->setPhase('Hold');
				$evaluation->setUpdated(new \DateTime());

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

				$holdText = '';
				if ($formData['holdForRequesterAdmit'] == "Yes") {
						$holdText .= 'Hold for requester admission. ';
				}
				if ($formData['holdForCourseInput'] == "Yes") {
						$holdText .= 'Hold for course input. ';
				}
				if ($formData['holdForTranscript'] == "Yes") {
						$holdText .= 'Hold for transcript. ';
				}
				// Remove the trailing space
				$holdText = substr($holdText, 0, -1);

				$trail->setBody('Put on hold by '.$coordinatorText.'. '.$holdText.' Phase set to Hold.');
				$trail->setBodyAnon('Put on hold by coordinator.');
				$trail->setCreated(new \DateTime());

				// Persist the entity
				$this->entityManager->persist($trail);
				$this->entityManager->flush(); // Save changes to the database
		}

		/**
		 * Pass
		 */

		/**
		 * Reassign
		 *
		 * @param Evaluation $evaluation
		 * @param array $formData
		 */
		public function reassignEvaluation(Evaluation $evaluation, array
		$formData): void
		{
				$assignee = $this->entityManager->getRepository(User::class)
					->findOneBy(['username' => $formData['assignee']]);
				$evaluation->setAssignee($assignee);
				$evaluation->setPhase('Department');
				$evaluation->setUpdated(new \DateTime());
				$evaluation->setTagReassigned(1);

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
				$assigneeText .= $assignee->getDisplayName();
				$assigneeText .= ' ('.$assignee->getUsername().')';

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

				$trail->setBody('Reassigned to '.$assigneeText.' by '.$coordinatorText
					.'. Phase set to Department.');
				$trail->setBodyAnon('Reassigned to department by coordinator.');
				$trail->setCreated(new \DateTime());

				// Persist the entity
				$this->entityManager->persist($trail);
				$this->entityManager->flush(); // Save changes to the database

				// Send email
				$emailService = new EmailService($this->entityManager);
				$emailService->emailToAssigneeUponNewAssignment($assignee->getUsername(), $evaluation);
		}

		/**
		 * Resubmit
		 *
		 * @param Evaluation $evaluation
		 * @param array $formData
		 */
		public function resubmitEvaluation(Evaluation $evaluation, array
		$formData): void
		{
			$evaluation->setPhase('Registrar 1');
			$evaluation->setUpdated(new \DateTime());

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
				$note->setVisibleToRequester(1);

				// Persist the entity
				$this->entityManager->persist($note);
				$this->entityManager->flush(); // Save changes to the database
			}

			// Create a trail
			$trail = new Trail();
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

			$trail->setBody('Resubmitted to Registrar 1 by '.$requesterText.'. Phase set to Registrar 1.');
			$trail->setBodyAnon('Resubmitted to Registrar by requester.');
			$trail->setCreated(new \DateTime());

			// Persist the entity
			$this->entityManager->persist($trail);
			$this->entityManager->flush(); // Save changes to the database
		}

		/**
		 * Spot-articulate
		 */
		public function spotArticulateEvaluation(Evaluation $evaluation, array $formData): void
		{
				$draftEqvText = ' Draft equiv: ';
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

				if ($this->security->getUser() instanceof User) {
						$evaluation->setAssignee($this->security->getUser());
				} elseif ($this->security->getUser() instanceof CasUser) {
						$userAtHand = $this->entityManager->getRepository(User::class)
							->findOneBy(['username' => $this->security->getUser()->getUserIdentifier()]);
						$evaluation->setAssignee($userAtHand);
				}

				$evaluation->setPhase('Registrar 2');
				$evaluation->setUpdated(new \DateTime());
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

	/**
	 * Look Up Requester
	 */
	public function lookUpRequesterEvaluation(Evaluation $evaluation, array $formData): void
	{
		// Re-process requester
		$lookupService = new LookupService($this->entityManager);
		$lookupService->processUser($evaluation->getRequester()->getUserIdentifier());

		$evaluation->setUpdated(new \DateTime());
		
		// Persist the entity
		$this->entityManager->persist($evaluation);
		$this->entityManager->flush(); // Save changes to the database

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

		$trail->setBody('Requester\'s information refreshed in web app by '.$coordinatorText.'.');
		$trail->setBodyAnon('Requester\'s information refreshed in web app by coordinator.');
		$trail->setCreated(new \DateTime());

		// Persist the entity
		$this->entityManager->persist($trail);
		$this->entityManager->flush(); // Save changes to the database
	}
}