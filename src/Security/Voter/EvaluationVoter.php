<?php

namespace App\Security\Voter;

use App\Entity\Evaluation;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class EvaluationVoter extends Voter
{
		public const ADMIN = 'admin';
		public const MANAGER = 'manager';
		public const COORDINATOR = 'coordinator';
		public const OBSERVER = 'observer';
		public const ASSIGNEE = 'assignee';
		public const REQUESTER = 'requester';
		public const CREATE = 'create';
		public const READ = 'read';
		public const UPDATE = 'update';
		public const DELETE = 'delete';
		public const ANNOTATE = 'annotate';
		public const ANNOTATE_AS_REQUESTER = 'annotate_as_requester';
		public const APPEND = 'append';
		public const ASSIGN = 'assign';
		public const EVALUATE = 'evaluate';
		public const EXAMPLE = 'example';
		public const FINALIZE = 'finalize';
		public const FORWARD = 'forward';
		public const FROM_COMPLETE_TO_HOLD = 'from_complete_to_hold';
		public const FROM_DEPT_TO_R1 = 'from_dept_to_r1';
		public const FROM_DEPT_TO_STUDENT = 'from_dept_to_student';
		public const FROM_R1_TO_STUDENT = 'from_r1_to_student';
		public const FROM_R2_TO_DEPT = 'from_r2_to_dept';
		public const FROM_R2_TO_STUDENT = 'from_r2_to_student';
		public const FROM_STUDENT_TO_R1 = 'from_student_to_r1';
		public const HIDE = 'hide';
		public const HOLD = 'hold';
		public const PASS = 'pass';
		public const REASSIGN = 'reassign';
		public const RESUBMIT = 'resubmit';
		public const SPOT_ARTICULATE = 'spot_articulate';

    protected function supports(string $attribute, mixed $subject): bool
    {
				$roles = [
						self::ADMIN,
						self::MANAGER,
						self::COORDINATOR,
						self::OBSERVER,
						self::ASSIGNEE,
						self::REQUESTER,
				];

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

				$attributes = explode('+', $attribute);

        return in_array($attributes[0], $roles)
						&& in_array($attributes[1], $verbs)
            && $subject instanceof \App\Entity\Evaluation;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

				$attributes = explode('+', $attribute);

				return match ($attributes[1]) {
						self::CREATE => $this->canCreate($subject, $user, $attributes[0]),
						self::READ => $this->canRead($subject, $user, $attributes[0]),
						self::UPDATE => $this->canUpdate($subject, $user, $attributes[0]),
						self::DELETE => $this->canDelete($subject, $user, $attributes[0]),
						self::ANNOTATE => $this->canAnnotate($subject, $user, $attributes[0]),
						self::ANNOTATE_AS_REQUESTER => $this->canAnnotateAsRequester($subject, $user, $attributes[0]),
						self::APPEND => $this->canAppend($subject, $user, $attributes[0]),
						self::ASSIGN => $this->canAssign($subject, $user, $attributes[0]),
						self::EVALUATE => $this->canEvaluate($subject, $user, $attributes[0]),
						self::EXAMPLE => $this->canExample($subject, $user, $attributes[0]),
						self::FINALIZE => $this->canFinalize($subject, $user, $attributes[0]),
						self::FORWARD => $this->canForward($subject, $user, $attributes[0]),
						self::FROM_COMPLETE_TO_HOLD => $this->canFromCompleteToHold($subject, $user, $attributes[0]),
						self::FROM_DEPT_TO_R1 => $this->canFromDeptToR1($subject, $user, $attributes[0]),
						self::FROM_DEPT_TO_STUDENT => $this->canFromDeptToStudent($subject, $user, $attributes[0]),
						self::FROM_R1_TO_STUDENT => $this->canFromR1ToStudent($subject, $user, $attributes[0]),
						self::FROM_R2_TO_DEPT => $this->canFromR2ToDept($subject, $user, $attributes[0]),
						self::FROM_R2_TO_STUDENT => $this->canFromR2ToStudent($subject, $user, $attributes[0]),
						self::FROM_STUDENT_TO_R1 => $this->canFromStudentToR1($subject, $user, $attributes[0]),
						self::HIDE => $this->canHide($subject, $user, $attributes[0]),
						self::HOLD => $this->canHold($subject, $user, $attributes[0]),
						self::PASS => $this->canPass($subject, $user, $attributes[0]),
						self::REASSIGN => $this->canReassign($subject, $user, $attributes[0]),
						self::RESUBMIT => $this->canResubmit($subject, $user, $attributes[0]),
						self::SPOT_ARTICULATE => $this->canSpotArticulate($subject, $user, $attributes[0]),
						default => false,
				};
		}

		private function canCreate(Evaluation $evaluation, UserInterface $user, string $context):	bool {
				return false;
		}

		private function canRead(Evaluation $evaluation, UserInterface $user, string $context): bool {
				if ($context === self::COORDINATOR) {
						return true;
				}
				return false;
		}

		private function canUpdate(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canDelete(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canAnnotate(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return true;
		}

		private function canAnnotateAsRequester(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canAppend(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return true;
		}

		private function canAssign(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return true;
		}

		private function canEvaluate(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canExample(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canFinalize(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canForward(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canFromCompleteToHold(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canFromDeptToR1(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canFromDeptToStudent(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canFromR1ToStudent(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canFromR2ToDept(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canFromR2ToStudent(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canFromStudentToR1(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canHide(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canHold(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canPass(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canReassign(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canResubmit(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canSpotArticulate(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

}

