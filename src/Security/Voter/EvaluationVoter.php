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
		public const UPDATE_AS_REQUESTER = 'update_as_requester';
		public const DELETE = 'delete';
		public const DELETE_AS_REQUESTER = 'delete_as_requester';
		public const ANNOTATE = 'annotate';
		public const ANNOTATE_AS_REQUESTER = 'annotate_as_requester';
		public const APPEND = 'append';
		public const APPEND_AS_REQUESTER = 'append_as_requester';
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
		public const LOOK_UP_REQUESTER = 'look_up_requester';

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
						self::UPDATE_AS_REQUESTER,
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
						self::SPOT_ARTICULATE,
						self::LOOK_UP_REQUESTER
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
						self::UPDATE_AS_REQUESTER => $this->canUpdateAsRequester($subject, $user, $attributes[0]),
						self::DELETE => $this->canDelete($subject, $user, $attributes[0]),
						self::DELETE_AS_REQUESTER => $this->canDeleteAsRequester($subject, $user, $attributes[0]),
						self::ANNOTATE => $this->canAnnotate($subject, $user, $attributes[0]),
						self::ANNOTATE_AS_REQUESTER => $this->canAnnotateAsRequester($subject, $user, $attributes[0]),
						self::APPEND => $this->canAppend($subject, $user, $attributes[0]),
						self::APPEND_AS_REQUESTER => $this->canAppendAsRequester($subject, $user, $attributes[0]),
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
						self::LOOK_UP_REQUESTER => $this->canLookUpRequester($subject, $user, $attributes[0]),
						default => false,
				};
		}

		private function canCreate(Evaluation $evaluation, UserInterface $user, string $context):	bool {
				return false;
		}

		private function canRead(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Admin, Manager, Coordinator, Observer, Assignee can read everything
				if (in_array($context, [self::ADMIN, self::MANAGER, self::COORDINATOR, self::OBSERVER, self::ASSIGNEE])) {
						return true;
				}
				// Requester can read only their own evaluations
				if ($context == self::REQUESTER && ($evaluation->getRequester()->getUserIdentifier() === $user->getUserIdentifier())) {
						return true;
				}
				return false;
		}

		private function canUpdate(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Admin, Manager, Coordinator can update everything
				if (in_array($context, [self::ADMIN, self::MANAGER, self::COORDINATOR])) {
						return true;
				}
				return false;
		}

		private function canUpdateAsRequester(Evaluation $evaluation, UserInterface $user, string $context): bool {
			// Requester can update only their own evaluations of phase "Student"
			if ($context == self::REQUESTER && ($evaluation->getRequester()->getUserIdentifier() === $user->getUserIdentifier()) && ($evaluation->getPhase() == 'Student')) {
				return true;
			}
			return false;
		}

		private function canDelete(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Admin, Manager can delete everything
				if (in_array($context, [self::ADMIN, self::MANAGER])) {
						return true;
				}
				return false;
		}

		private function canDeleteAsRequester(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Requester can delete only their own evaluations of phase "Student"
				if ($context == self::REQUESTER && ($evaluation->getRequester()->getUserIdentifier() === $user->getUserIdentifier()) && ($evaluation->getPhase() == 'Student')) {
						return true;
				}
				return false;
		}

		private function canAnnotate(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Admin, Manager, Coordinator, Observer, Assignee can annotate everything
				if (in_array($context, [self::ADMIN, self::MANAGER, self::COORDINATOR, self::OBSERVER, self::ASSIGNEE])) {
						return true;
				}
				return false;
		}

		private function canAnnotateAsRequester(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Requester can annotate-as-requester only their own evaluations
				if ($context == self::REQUESTER && ($evaluation->getRequester()->getUserIdentifier() === $user->getUserIdentifier())) {
						return true;
				}
				return false;
		}

		private function canAppend(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Admin, Manager, Coordinator can append to everything
				if (in_array($context, [self::ADMIN, self::MANAGER, self::COORDINATOR])) {
						return true;
				}
				return false;
		}

		private function canAppendAsRequester(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Requester can append-as-requester only their own evaluations
				if ($context == self::REQUESTER && ($evaluation->getRequester()->getUserIdentifier() === $user->getUserIdentifier())) {
						return true;
				}
				return false;
		}

		private function canAssign(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Admin, Manager, Coordinator can assign any evaluation with phase R1
				if (in_array($context, [self::ADMIN, self::MANAGER, self::COORDINATOR]) && ($evaluation->getPhase() == 'Registrar 1')) {
						return true;
				}
				return false;
		}

		private function canEvaluate(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Admin, Manager, Coordinator can evaluate any evaluation with phase "Department"
				if (in_array($context, [self::ADMIN, self::MANAGER, self::COORDINATOR]) && ($evaluation->getPhase() == 'Department')) {
						return true;
				}
				// Assignee can evaluate only evaluations assigned to them with phase "Department"
				if ($context == self::ASSIGNEE && ($evaluation->getPhase() == 'Department') && ($evaluation->getAssignee()->getUserIdentifier() === $user->getUserIdentifier())) {
						return true;
				}
				return false;
		}

		private function canExample(Evaluation $evaluation, UserInterface $user, string $context): bool {
				return false;
		}

		private function canFinalize(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Admin, Manager, Coordinator can finalize any evaluation with phase "R2"
				if (in_array($context, [self::ADMIN, self::MANAGER, self::COORDINATOR]) && ($evaluation->getPhase() == 'Registrar 2')) {
						return true;
				}
				return false;
		}

		private function canForward(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Assignee can forward only evaluations assigned to them with phase "Department"
				if ($context == self::ASSIGNEE && ($evaluation->getPhase() == 'Department') && ($evaluation->getAssignee()->getUserIdentifier() === $user->getUserIdentifier())) {
						return true;
				}
				return false;
		}

		private function canFromCompleteToHold(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Admin, Manager, Coordinator can move to hold any evaluation with phase "Complete"
				if (in_array($context, [self::ADMIN, self::MANAGER, self::COORDINATOR]) && ($evaluation->getPhase() == 'Complete')) {
						return true;
				}
				return false;
		}

		private function canFromDeptToR1(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Admin, Manager, Coordinator can move to R1 any evaluation with phase "Department"
				if (in_array($context, [self::ADMIN, self::MANAGER, self::COORDINATOR]) && ($evaluation->getPhase() == 'Department')) {
						return true;
				}
				return false;
		}

		private function canFromDeptToStudent(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Admin, Manager, Coordinator can move to Student any evaluation with phase "Department"
				if (in_array($context, [self::ADMIN, self::MANAGER, self::COORDINATOR]) && ($evaluation->getPhase() == 'Department')) {
						return true;
				}
				return false;
		}

		private function canFromR1ToStudent(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Admin, Manager, Coordinator can move to Student any evaluation with phase "Registrar 1"
				if (in_array($context, [self::ADMIN, self::MANAGER, self::COORDINATOR]) && ($evaluation->getPhase() == 'Registrar 1')) {
						return true;
				}
				return false;
		}

		private function canFromR2ToDept(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Admin, Manager, Coordinator can move to Department any evaluation with phase "Registrar 2"
				if (in_array($context, [self::ADMIN, self::MANAGER, self::COORDINATOR]) && ($evaluation->getPhase() == 'Registrar 2')) {
						return true;
				}
				return false;
		}

		private function canFromR2ToStudent(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Admin, Manager, Coordinator can move to Student any evaluation with phase "Registrar 2"
				if (in_array($context, [self::ADMIN, self::MANAGER, self::COORDINATOR]) && ($evaluation->getPhase() == 'Registrar 2')) {
						return true;
				}
				return false;
		}

		private function canFromStudentToR1(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Admin, Manager, Coordinator can move to R1 any evaluation with phase "Student"
				if (in_array($context, [self::ADMIN, self::MANAGER, self::COORDINATOR]) && ($evaluation->getPhase() == 'Student')) {
						return true;
				}
				return false;
		}

		private function canHide(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Coordinator can hide anything
				if ($context == self::COORDINATOR) {
						return true;
				}
				return false;
		}

		private function canHold(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Admin, Manager, Coordinator can put hold any evaluation with phase Registrar 2
				if (in_array($context, [self::ADMIN, self::MANAGER, self::COORDINATOR]) && ($evaluation->getPhase() == 'Registrar 2')) {
						return true;
				}
				return false;
		}

		private function canPass(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Assignee can pass only evaluations assigned to them with phase "Department"
				if ($context == self::ASSIGNEE && ($evaluation->getPhase() == 'Department') && ($evaluation->getAssignee()->getUserIdentifier() === $user->getUserIdentifier())) {
						return true;
				}
				return false;
		}

		private function canReassign(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Admin, Manager, Coordinator can reassign any evaluation with phase "Department"
				if (in_array($context, [self::ADMIN, self::MANAGER, self::COORDINATOR]) && ($evaluation->getPhase() == 'Department')) {
						return true;
				}
				return false;
		}

		private function canResubmit(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Requester can resubmit only their own evaluations of phase "Student"
				if ($context == self::REQUESTER && ($evaluation->getRequester()->getUserIdentifier() === $user->getUserIdentifier()) && ($evaluation->getPhase() == 'Student')) {
						return true;
				}
				return false;
		}

		private function canSpotArticulate(Evaluation $evaluation, UserInterface $user, string $context): bool {
				// Admin, Manager, Coordinator can spot articulate any evaluation with phase "R1"
				if (in_array($context, [self::ADMIN, self::MANAGER, self::COORDINATOR]) && ($evaluation->getPhase() == 'Registrar 1')) {
						return true;
				}
				return false;
		}

		private function canLookUpRequester(Evaluation $evaluation, UserInterface $user, string $context): bool {
			// Admin, Manager, Coordinator can spot articulate any evaluation with phase "R1"
			if (in_array($context, [self::ADMIN, self::MANAGER, self::COORDINATOR])) {
				return true;
			}
			return false;
	}

}

