<?php

namespace App\Security\Voter;

use App\Entity\User;
use EcPhp\CasBundle\Security\Core\User\CasUser;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomRoleVoter extends Voter
{
    public const ADMIN = 'admin';
		public const MANAGER = 'manager';
    public const COORDINATOR = 'coordinator';
		public const OBSERVER = 'observer';
		public const ASSIGNEE = 'assignee';
		public const REQUESTER = 'requester';

    protected function supports(string $attribute, mixed $subject): bool
    {
        $existingRoles = [
						self::ADMIN,
						self::MANAGER,
						self::COORDINATOR,
						self::OBSERVER,
						self::ASSIGNEE,
						self::REQUESTER,
				];
        return in_array($attribute, $existingRoles) && (is_null($subject));
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

				$userRoles = [];
				if ($user instanceof User) {
						$userRoles = $user->attributes()['profile']['roles'];
				} elseif ($user instanceof CasUser) {
						$userRoles = $user->getAttributes()['profile']['roles'];
				}

				return in_array('ROLE_'.strtoupper($attribute), $userRoles);
    }
}
