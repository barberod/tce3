<?php

namespace App\Service;

use App\Entity\Course;
use App\Entity\Evaluation;
use App\Entity\Institution;
use Doctrine\ORM\EntityManagerInterface;

class EvaluationFormDefaultsService
{
		private FormOptionsService $formOptionsService;
		private EntityManagerInterface $entityManager;

		public function __construct(
			EntityManagerInterface $entityManager
		) {
			$this->entityManager = $entityManager;
			$this->formOptionsService = new FormOptionsService($this->entityManager);
		}

		/**
		 * Form defaults for the Evaluation Update form
		 */
		public function getEvaluationUpdateDefaults(Evaluation $evaluation): array
		{
			$lookup = new LookupService($this->entityManager);
			$requesterAttributes = $lookup->getRequesterAttributes($evaluation->getRequester()->getOrgID());

			return array(
				'created' => $evaluation->getCreated(),
				'requiredForAdmission' => $evaluation->getReqAdmin(),
				'hasLab' => $this->getHasLab($evaluation),
				'locatedUsa' => $this->getLocatedUsa($evaluation),
				'state' => $this->getState($evaluation),
				'institution' => $this->getInstitution($evaluation),,
				'institutionListed' => $this->getInstitutionListed($evaluation),
				'institutionCountry' => $evaluation->getInstitutionCountry(),
				'institutionName' => $evaluation->getInstitutionOther()
			);
		}

		public function getHasLab(Evaluation $evaluation): string
		{
			if (
				$evaluation->getLabSubjCode() ||
				$evaluation->getLabCrseNum() ||
				$evaluation->getLabTitle() ||
				$evaluation->getLabTerm() ||
				$evaluation->getLabCreditHrs() ||
				$evaluation->getLabCreditBasis()
			) {
				return 'Yes';
			}
			return 'No';
		}

		public function getLocatedUsa(Evaluation $evaluation): string
		{
			if ($evaluation->getInstitution()) {
				return 'Yes';
			} elseif ($evaluation->getInstitutionCountry() === 'United States') {
				return 'Yes';
			}
			return 'No';
		}

		public function getState(Evaluation $evaluation): string
		{
			if ($evaluation->getInstitution()) {
				return $evaluation->getInstitution()->getState() ?: '';
			}
			return '';
		}

		public function getInstitution(Evaluation $evaluation): string
		{
			if ($evaluation->getInstitution()) {
				return $evaluation->getInstitution()->getId() ?: '';
			}
			return '';
		}

		public function getInstitutionListed(Evaluation $evaluation): string
		{
			if ($evaluation->getInstitution()) {
				return 'Yes';
			}
			return 'No';
		}

		// end of form defaults for the Evaluation Update form

		/**
		 * Form defaults for the Evaluation Finalize form
		 */
		public function getEvaluationFinalizeDefaults(Evaluation $evaluation): array
		{
			$lookup = new LookupService($this->entityManager);
			$requesterAttributes = $lookup->getRequesterAttributes($evaluation->getRequester()->getOrgID());

			return array(
				'eqvCnt' => $this->getDraftEquivCount($evaluation),
				'eqv1SubjCode' => $this->getSubjectCodeFromDraftEquivCourse($evaluation->getDraftEquiv1Course()),
				'eqv1Options' => $this->formOptionsService->getCoursesBySubjectCode($this->getSubjectCodeFromDraftEquivCourse($evaluation->getDraftEquiv1Course())),
				'eqv1' => $this->getCourseIdFromDraftEquivCourse($evaluation->getDraftEquiv1Course()),
				'eqv1Hrs' => $evaluation->getDraftEquiv1CreditHrs(),
				'eqv1Opr' => $evaluation->getDraftEquiv1Operator(),
				'eqv2SubjCode' => $this->getSubjectCodeFromDraftEquivCourse($evaluation->getDraftEquiv2Course()),
				'eqv2Options' => $this->formOptionsService->getCoursesBySubjectCode($this->getSubjectCodeFromDraftEquivCourse($evaluation->getDraftEquiv2Course())),
				'eqv2' => $this->getCourseIdFromDraftEquivCourse($evaluation->getDraftEquiv2Course()),
				'eqv2Hrs' => $evaluation->getDraftEquiv2CreditHrs(),
				'eqv2Opr' => $evaluation->getDraftEquiv2Operator(),
				'eqv3SubjCode' => $this->getSubjectCodeFromDraftEquivCourse($evaluation->getDraftEquiv3Course()),
				'eqv3Options' => $this->formOptionsService->getCoursesBySubjectCode($this->getSubjectCodeFromDraftEquivCourse($evaluation->getDraftEquiv3Course())),
				'eqv3' => $this->getCourseIdFromDraftEquivCourse($evaluation->getDraftEquiv3Course()),
				'eqv3Hrs' => $evaluation->getDraftEquiv3CreditHrs(),
				'eqv3Opr' => $evaluation->getDraftEquiv3Operator(),
				'eqv4SubjCode' => $this->getSubjectCodeFromDraftEquivCourse($evaluation->getDraftEquiv4Course()),
				'eqv4Options' => $this->formOptionsService->getCoursesBySubjectCode($this->getSubjectCodeFromDraftEquivCourse($evaluation->getDraftEquiv4Course())),
				'eqv4' => $this->getCourseIdFromDraftEquivCourse($evaluation->getDraftEquiv4Course()),
				'eqv4Hrs' => $evaluation->getDraftEquiv4CreditHours(),
				'policy' => $this->getPolicyDefaultFromDraftPolicy($evaluation->getDraftPolicy()),
				'lookup' => $this->styleRequesterAttributesAsText($requesterAttributes),
				'requesterType' => $lookup->getRequesterType($requesterAttributes)
			);
		}

		public function getDraftEquivCount(Evaluation $evaluation): int
		{
			$draftEquivCount = 0;
			if (!is_null($evaluation->getDraftEquiv1Course()) && $evaluation->getDraftEquiv1Course() !== '') {
				$draftEquivCount++;
			}
			if (!is_null($evaluation->getDraftEquiv2Course()) && $evaluation->getDraftEquiv2Course() !== '') {
				$draftEquivCount++;
			}
			if (!is_null($evaluation->getDraftEquiv3Course()) && $evaluation->getDraftEquiv3Course() !== '') {
				$draftEquivCount++;
			}
			if (!is_null($evaluation->getDraftEquiv4Course()) && $evaluation->getDraftEquiv4Course() !== '') {
				$draftEquivCount++;
			}
			return $draftEquivCount;
		}

		public function getSubjectCodeFromDraftEquivCourse(?string $draftEquivCourse): ?string
		{
			if (is_null($draftEquivCourse)) {
				return null;
			}
			return substr($draftEquivCourse, 0, strpos($draftEquivCourse, ' '));
		}

		public function getCourseNumberFromDraftEquivCourse(?string $draftEquivCourse): ?string
		{
			if (is_null($draftEquivCourse)) {
				return null;
			}
			return substr($draftEquivCourse, strpos($draftEquivCourse, ' ') + 1);
		}

		public function getCourseIdFromDraftEquivCourse(?string $draftEquivCourse): ?string
		{
			if (is_null($draftEquivCourse)) {
				return null;
			}
			$param1 = $this->getSubjectCodeFromDraftEquivCourse($draftEquivCourse);
			$param2 = $this->getCourseNumberFromDraftEquivCourse($draftEquivCourse);
			$course = $this->entityManager->getRepository(Course::class)->findOneBy(['subjectCode' => $param1, 'courseNumber' => $param2]);
			if (is_null($course)) {
				return null;
			}
			return $course->getId();
		}

		public function getPolicyDefaultFromDraftPolicy(?string $draftPolicy): ?string
		{
			if ($draftPolicy === 'Policy') {
				return 'Yes';
			} elseif ($draftPolicy === 'Not Policy') {
				return 'No';
			} else {
				return null;
			}
		}

		public function styleRequesterAttributesAsText(array $requesterAttributes): string
		{
			$requesterAttributesText = '';
			foreach ($requesterAttributes as $key => $value) {
				if (is_array($value)) {
					$value = implode(', ', $value);
				}
				$requesterAttributesText .= $key . ': ' . $value . PHP_EOL;
			}
			return $requesterAttributesText;
		}

		// end of form defaults for the Evaluation Finalize form
}