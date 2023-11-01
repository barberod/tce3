<?php

namespace App\Service;

use App\Entity\Evaluation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EcPhp\CasBundle\Security\Core\User\CasUser;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class EvaluationOptionsService
{

		protected EntityManagerInterface $entityManager;

		protected AuthorizationCheckerInterface $authorizationChecker;

		public function __construct(
			EntityManagerInterface $entityManager,
			AuthorizationCheckerInterface $authorizationChecker
		) {
				$this->entityManager = $entityManager;
				$this->authorizationChecker = $authorizationChecker;
		}

		public function getOptions(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): array {
				$options = [];
				$options['update'] = $this->possibleUpdate(
					$context,
					$evaluation,
					$user
				);
				$options['delete'] = $this->possibleDelete(
					$context,
					$evaluation,
					$user
				);
				$options['annotate'] = $this->possibleAnnotate(
					$context,
					$evaluation,
					$user
				);
				$options['annotate_as_requester'] = $this->possibleAnnotateAsRequester(
					$context,
					$evaluation,
					$user
				);
				$options['append'] = $this->possibleAppend(
					$context,
					$evaluation,
					$user
				);
				$options['assign'] = $this->possibleAssign(
					$context,
					$evaluation,
					$user
				);
				$options['evaluate'] = $this->possibleEvaluate(
					$context,
					$evaluation,
					$user
				);
				$options['example'] = $this->possibleExample(
					$context,
					$evaluation,
					$user
				);
				$options['finalize'] = $this->possibleFinalize(
					$context,
					$evaluation,
					$user
				);
				$options['forward'] = $this->possibleForward(
					$context,
					$evaluation,
					$user
				);
				$options['from_complete_to_hold'] = $this->possibleFromCompleteToHold(
					$context,
					$evaluation,
					$user
				);
				$options['from_dept_to_r1'] = $this->possibleFromDeptToR1(
					$context,
					$evaluation,
					$user
				);
				$options['from_dept_to_student'] = $this->possibleFromDeptToStudent(
					$context,
					$evaluation,
					$user
				);
				$options['from_r1_to_student'] = $this->possibleFromR1ToStudent(
					$context,
					$evaluation,
					$user
				);
				$options['from_r2_to_dept'] = $this->possibleFromR2ToDept(
					$context,
					$evaluation,
					$user
				);
				$options['from_r2_to_student'] = $this->possibleFromR2ToStudent(
					$context,
					$evaluation,
					$user
				);
				$options['from_student_to_r1'] = $this->possibleFromStudentToR1(
					$context,
					$evaluation,
					$user
				);
				$options['hide'] = $this->possibleHide($context, $evaluation, $user);
				$options['hold'] = $this->possibleHold($context, $evaluation, $user);
				$options['pass'] = $this->possiblePass($context, $evaluation, $user);
				$options['reassign'] = $this->possibleReassign(
					$context,
					$evaluation,
					$user
				);
				$options['resubmit'] = $this->possibleResubmit(
					$context,
					$evaluation,
					$user
				);
				$options['spot_articulate'] = $this->possibleSpotArticulate(
					$context,
					$evaluation,
					$user
				);

				return $options;
		}

		public function possibleUpdate(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted('UPDATE', $evaluation)
					&& $this->userFitsContext($context, $user);
		}

		private function userFitsContext(string $context, UserInterface $user): bool
		{
				if ($user instanceof User) {
						$roles = $user->attributes()['profile']['roles'];
				} elseif ($user instanceof CasUser) {
						$roles = $user->getAttributes()['profile']['roles'];
				} else {
						$roles = [];
				}

				return match ($context) {
						'manager' => in_array('ROLE_MANAGER', $roles),
						'coordinator' => in_array('ROLE_COORDINATOR', $roles),
						'observer' => in_array('ROLE_OBSERVER', $roles),
						'assignee' => in_array('ROLE_ASSIGNEE', $roles),
						'requester' => in_array('ROLE_REQUESTER', $roles),
						default => false,
				};
		}

		public function possibleDelete(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted('DELETE', $evaluation)
					&& $this->userFitsContext($context, $user);
		}

		public function possibleAnnotate(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted('ANNOTATE', $evaluation)
					&& $this->userFitsContext($context, $user);
		}

		public function possibleAnnotateAsRequester(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted(
						'ANNOTATE_AS_REQUESTER',
						$evaluation
					)
					&& $this->userFitsContext($context, $user);
		}

		public function possibleAppend(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted('APPEND', $evaluation)
					&& $this->userFitsContext($context, $user);
		}

		public function possibleAssign(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted('ASSIGN', $evaluation)
					&& $this->userFitsContext($context, $user);
		}

		public function possibleEvaluate(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted('EVALUATE', $evaluation)
					&& $this->userFitsContext($context, $user);
		}

		public function possibleExample(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted('EXAMPLE', $evaluation)
					&& $this->userFitsContext($context, $user);
		}

		public function possibleFinalize(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted('FINALIZE', $evaluation)
					&& $this->userFitsContext($context, $user);
		}

		public function possibleForward(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted('FORWARD', $evaluation)
					&& $this->userFitsContext($context, $user);
		}

		public function possibleFromCompleteToHold(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted(
						'FROM_COMPLETE_TO_HOLD',
						$evaluation
					)
					&& $this->userFitsContext($context, $user);
		}

		public function possibleFromDeptToR1(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted(
						'FROM_DEPT_TO_R1',
						$evaluation
					) &&
					$this->userFitsContext($context, $user);
		}

		public function possibleFromDeptToStudent(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted(
						'FROM_DEPT_TO_STUDENT',
						$evaluation
					) &&
					$this->userFitsContext($context, $user);
		}

		public function possibleFromR1ToStudent(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted(
						'FROM_R1_TO_STUDENT',
						$evaluation
					) &&
					$this->userFitsContext($context, $user);
		}

		public function possibleFromR2ToDept(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted(
						'FROM_R2_TO_DEPT',
						$evaluation
					) &&
					$this->userFitsContext($context, $user);
		}

		public function possibleFromR2ToStudent(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted(
						'FROM_R2_TO_STUDENT',
						$evaluation
					) &&
					$this->userFitsContext($context, $user);
		}

		public function possibleFromStudentToR1(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted(
						'FROM_STUDENT_TO_R1',
						$evaluation
					) &&
					$this->userFitsContext($context, $user);
		}

		public function possibleHide(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted('HIDE', $evaluation)
					&& $this->userFitsContext($context, $user);
		}

		public function possibleHold(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted('HOLD', $evaluation)
					&& $this->userFitsContext($context, $user);
		}

		public function possiblePass(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted('PASS', $evaluation)
					&& $this->userFitsContext($context, $user);
		}

		public function possibleReassign(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted(
						'REASSIGN',
						$evaluation
					) &&
					$this->userFitsContext($context, $user);
		}

		public function possibleResubmit(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted(
						'RESUBMIT',
						$evaluation
					) &&
					$this->userFitsContext($context, $user);
		}

		public function possibleSpotArticulate(
			string $context,
			Evaluation $evaluation,
			UserInterface $user
		): bool {
				return $this->authorizationChecker->isGranted(
						'SPOT_ARTICULATE',
						$evaluation
					) &&
					$this->userFitsContext($context, $user);
		}

}