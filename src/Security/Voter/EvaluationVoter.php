<?php

namespace App\Security\Voter;

use App\Entity\Evaluation;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class EvaluationVoter extends Voter
{
		public const CREATE = 'CREATE';
		public const READ = 'READ';
		public const UPDATE = 'UPDATE';
		public const DELETE = 'DELETE';
		public const ANNOTATE = 'ANNOTATE';
		public const ANNOTATE_AS_REQUESTER = 'ANNOTATE_AS_REQUESTER';
		public const APPEND = 'APPEND';
		public const ASSIGN = 'ASSIGN';
		public const EVALUATE = 'EVALUATE';
		public const EXAMPLE = 'EXAMPLE';
		public const FINALIZE = 'FINALIZE';
		public const FORWARD = 'FORWARD';
		public const FROM_COMPLETE_TO_HOLD = 'FROM_COMPLETE_TO_HOLD';
		public const FROM_DEPT_TO_R1 = 'FROM_DEPT_TO_R1';
		public const FROM_DEPT_TO_STUDENT = 'FROM_DEPT_TO_STUDENT';
		public const FROM_R1_TO_STUDENT = 'FROM_R1_TO_STUDENT';
		public const FROM_R2_TO_DEPT = 'FROM_R2_TO_DEPT';
		public const FROM_R2_TO_STUDENT = 'FROM_R2_TO_STUDENT';
		public const FROM_STUDENT_TO_R1 = 'FROM_STUDENT_TO_R1';
		public const HIDE = 'HIDE';
		public const HOLD = 'HOLD';
		public const PASS = 'PASS';
		public const REASSIGN = 'REASSIGN';
		public const RESUBMIT = 'RESUBMIT';
		public const SPOT_ARTICULATE = 'SPOT_ARTICULATE';

    protected function supports(string $attribute, mixed $subject): bool
    {
				$verbs = [
						self::CREATE,
						self::READ,
						self::UPDATE,
						self::DELETE,
						self::ANNOTATE,
						self::ANNOTATE_AS_REQUESTER,
						self::APPEND,
						self::ASSIGN,
						self::EVALUATE,
						self::EXAMPLE,
						self::FINALIZE,
						self::FORWARD,
						self::FROM_COMPLETE_TO_HOLD,
						self::FROM_DEPT_TO_R1,
						self::FROM_DEPT_TO_STUDENT,
						self::FROM_R1_TO_STUDENT,
						self::FROM_R2_TO_DEPT,
						self::FROM_R2_TO_STUDENT,
						self::FROM_STUDENT_TO_R1,
						self::HIDE,
						self::HOLD,
						self::PASS,
						self::REASSIGN,
						self::RESUBMIT,
						self::SPOT_ARTICULATE
				];

        return in_array($attribute, $verbs)
            && $subject instanceof \App\Entity\Evaluation;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

				return match ($attribute) {
						self::CREATE => $this->canCreate($subject, $user),
						self::READ => $this->canRead($subject, $user),
						self::UPDATE => $this->canUpdate($subject, $user),
						self::DELETE => $this->canDelete($subject, $user),
						self::ANNOTATE => $this->canAnnotate($subject, $user),
						self::ANNOTATE_AS_REQUESTER => $this->canAnnotateAsRequester(
							$subject,
							$user
						),
						self::APPEND => $this->canAppend($subject, $user),
						self::ASSIGN => $this->canAssign($subject, $user),
						self::EVALUATE => $this->canEvaluate($subject, $user),
						self::EXAMPLE => $this->canExample($subject, $user),
						self::FINALIZE => $this->canFinalize($subject, $user),
						self::FORWARD => $this->canForward($subject, $user),
						self::FROM_COMPLETE_TO_HOLD => $this->canFromCompleteToHold(
							$subject,
							$user
						),
						self::FROM_DEPT_TO_R1 => $this->canFromDeptToR1($subject, $user),
						self::FROM_DEPT_TO_STUDENT => $this->canFromDeptToStudent(
							$subject,
							$user
						),
						self::FROM_R1_TO_STUDENT => $this->canFromR1ToStudent(
							$subject,
							$user
						),
						self::FROM_R2_TO_DEPT => $this->canFromR2ToDept($subject, $user),
						self::FROM_R2_TO_STUDENT => $this->canFromR2ToStudent(
							$subject,
							$user
						),
						self::FROM_STUDENT_TO_R1 => $this->canFromStudentToR1(
							$subject,
							$user
						),
						self::HIDE => $this->canHide($subject, $user),
						self::HOLD => $this->canHold($subject, $user),
						self::PASS => $this->canPass($subject, $user),
						self::REASSIGN => $this->canReassign($subject, $user),
						self::RESUBMIT => $this->canResubmit($subject, $user),
						self::SPOT_ARTICULATE => $this->canSpotArticulate($subject, $user),
						default => false,
				};
		}

		private function canCreate(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canRead(Evaluation $evaluation, UserInterface $user): bool {
				return true;
		}

		private function canUpdate(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canDelete(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canAnnotate(Evaluation $evaluation, UserInterface $user): bool {
				return true;
		}

		private function canAnnotateAsRequester(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canAppend(Evaluation $evaluation, UserInterface $user): bool {
				return true;
		}

		private function canAssign(Evaluation $evaluation, UserInterface $user): bool {
				return true;
		}

		private function canEvaluate(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canExample(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canFinalize(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canForward(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canFromCompleteToHold(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canFromDeptToR1(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canFromDeptToStudent(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canFromR1ToStudent(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canFromR2ToDept(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canFromR2ToStudent(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canFromStudentToR1(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canHide(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canHold(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canPass(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canReassign(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canResubmit(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

		private function canSpotArticulate(Evaluation $evaluation, UserInterface $user): bool {
				return false;
		}

}

