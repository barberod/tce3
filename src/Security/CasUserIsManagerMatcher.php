<?php

namespace App\Security;

use EcPhp\CasBundle\Security\Core\User\CasUser;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

class CasUserIsManagerMatcher implements RequestMatcherInterface
{
		protected $security;

		public function __construct(Security $security)
		{
				$this->security = $security;
		}

		public function matches(Request $request): bool
		{
				if (false === $this->security->isGranted('ROLE_CAS_AUTHENTICATED')) {
						return false;
				}

				if (false === $this->security->getUser() instanceof CasUser) {
						return false;
				}

				return in_array('ROLE_MANAGER', $this->security->getUser()
					->getAttributes()['profile']['roles']);
		}
}