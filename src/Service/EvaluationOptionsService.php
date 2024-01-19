<?php

namespace App\Service;

use App\Entity\Evaluation;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class EvaluationOptionsService
{
		protected AuthorizationCheckerInterface $authorizationChecker;

		public function __construct(
			AuthorizationCheckerInterface $authorizationChecker
		) {
				$this->authorizationChecker = $authorizationChecker;
		}

		public function getEvaluationVerbs(): array {
				$verbs = [];
				$verbs[] = 'create';
				$verbs[] = 'read';
				$verbs[] = 'update';
				$verbs[] = 'update_as_requester';
				$verbs[] = 'delete';
				$verbs[] = 'annotate';
				$verbs[] = 'annotate_as_requester';
				$verbs[] = 'append';
				$verbs[] = 'append_as_requester';
				$verbs[] = 'assign';
				$verbs[] = 'evaluate';
				$verbs[] = 'example';
				$verbs[] = 'finalize';
				$verbs[] = 'forward';
				$verbs[] = 'from_complete_to_hold';
				$verbs[] = 'from_dept_to_r1';
				$verbs[] = 'from_dept_to_student';
				$verbs[] = 'from_r1_to_student';
				$verbs[] = 'from_r2_to_dept';
				$verbs[] = 'from_r2_to_student';
				$verbs[] = 'from_student_to_r1';
				$verbs[] = 'hide';
				$verbs[] = 'hold';
				$verbs[] = 'pass';
				$verbs[] = 'reassign';
				$verbs[] = 'remove_hold';
				$verbs[] = 'resubmit';
				$verbs[] = 'spot_articulate';
				$verbs[] = 'look_up_requester';
				return $verbs;
		}

		public function getOptions(string $context, Evaluation $evaluation): array
		{
				$options = [];
				foreach ($this->getEvaluationVerbs() as $verb) {
						$attribute = $context.'+'.$verb;
						$options[$verb] = $this->authorizationChecker->isGranted($attribute, $evaluation);
				}
				return $options;
		}
}