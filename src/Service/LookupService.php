<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Validator\Constraints\Date;

class LookupService
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    public function processUser(string $givenUsername): User
    {
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['username'=>$givenUsername]);

        if (!$existingUser) {
            $this->saveNewUser($givenUsername);
        } else {
            $this->updateExistingUserIfNeeded($existingUser);
        }
        
        return $this->entityManager->getRepository(User::class)->findOneBy(['username'=>$givenUsername]);
    }

    protected function saveNewUser(string $givenUsername): User {
        $user = new User();
        // $userData = $this->getDevUserData($givenUsername);
        $userData = $this->getUserData($givenUsername);
        $user->setUsername($userData['username']);
        $user->setOrgID($userData['org_id']);
        $user->setDisplayName($userData['display_name']);
        $user->setEmail($userData['email']);
        $user->setCategory($userData['category']);
        $user->setStatus($userData['status']);
        $user->setFrozen($userData['frozen']);
        $user->setLoadedFrom($userData['loaded_from']);
        $user->setRoles($userData['roles']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    protected function updateExistingUserIfNeeded(User $existingUser): User {
        if ($existingUser->getFrozen() === 1) {
            return $existingUser;
        }

        // $userData = $this->getDevUserData($existingUser->getUsername());
        $userData = $this->getUserData($existingUser->getUsername());
        if (
            $existingUser->getOrgID() !== $userData["org_id"] ||
            $existingUser->getDisplayName() !== $userData["display_name"] ||
            $existingUser->getEmail() !== $userData["email"] ||
            $existingUser->getCategory() !== $userData["category"] ||
            $existingUser->getStatus() !== $userData["status"] ||
            $existingUser->getRoles() !== $userData["roles"]
        ) {
            $existingUser->setOrgID($userData["org_id"]);
            $existingUser->setDisplayName($userData["display_name"]);
            $existingUser->setEmail($userData["email"]);
            $existingUser->setCategory($userData["category"]);
            $existingUser->setStatus($userData["status"]);
            $existingUser->setRoles($userData["roles"]);
            $existingUser->setLoadedFrom($userData["loaded_from"]);
            $this->entityManager->flush();

            return $this->entityManager->getRepository(User::class)->findOneBy(['username'=>$existingUser->getUsername()]);
        }

        return $existingUser;
    }

    public function getUserData(string $givenUsername): array
    {
        $ldap = Ldap::create('ext_ldap', ['connection_string' => $_ENV["LDAP_HOST"].':'.(int)$_ENV["LDAP_PORT"]]);
        $ldap->bind($_ENV["LDAP_DN"], $_ENV["LDAP_PW"]);
        $query = $ldap->query('ou=accounts,ou=gtaccounts,ou=departments,dc=gted,dc=gatech,dc=edu', "(&(uid={$givenUsername}))");
        $result = $query->execute();
        return $this->crosswalkLdapDataToUserData($givenUsername, $result[0]);
    }

    private function crosswalkLdapDataToUserData(string $givenUsername, Entry $entry): array {
        $userData = array();
        $userData['username'] = $givenUsername;
        $userData['org_id'] = $this->getIndexZero($entry->getAttribute('gtGTID'));
        $userData['display_name'] = $this->getIndexZero($entry->getAttribute('displayName'));
        $userData['email'] = $this->getIndexZero($entry->getAttribute('gtPrimaryEmailAddress'));
        // $userData['category'] = $this->getIndexZero($entry->getAttribute('eduPersonPrimaryAffiliation'));
        $userData['category'] = $this->getRequesterType($this->getRequesterAttributes($userData['org_id']));
        $userData['status'] = 1;
        $userData['frozen'] = 0;
        $userData['loaded_from'] = 'ldap-'.$this->generateRandomString(5);
        $userData['roles'] = $this->determineRoles($entry);
        return $userData;
    }

    private function determineRoles(Entry $entry): array {
        $roles = array();
        if ($this->isRequester($entry)) { $roles[] = User::ROLE_REQUESTER; };
        if ($this->isAssignee($entry)) { $roles[] = User::ROLE_ASSIGNEE; };
        if ($this->isObserver($entry)) { $roles[] = User::ROLE_OBSERVER; };
        if ($this->isCoordinator($entry)) { $roles[] = User::ROLE_COORDINATOR; };
        if ($this->isManager($entry)) { $roles[] = User::ROLE_MANAGER; };
        if ($this->isAdmin($entry)) { $roles[] = User::ROLE_ADMIN; };
        return $roles;
    }

    private function isRequester(Entry $entry): bool {
        if (
            $this->isAdmin($entry) || 
            $this->isManager($entry) || 
            $this->isCoordinator($entry) ||
            $this->isObserver($entry) ||
            $this->isAssignee($entry)
        ) {
            return true;
        }

        if (
            (in_array("student@gt", $entry->getAttribute('eduPersonScopedAffiliation'))) ||
            (in_array("undergrad-applicant@gt", $entry->getAttribute('eduPersonScopedAffiliation'))) ||
            (in_array("credit-applicant-confirmed@gt", $entry->getAttribute('eduPersonScopedAffiliation')))
        ) {
            return true;
        }
        
        return false;
    }

    private function isAssignee(Entry $entry): bool {
        if ($this->isAdmin($entry) || $this->isManager($entry) || $this->isCoordinator($entry)) {
            return true;
        }
        if (
            (in_array("vm61", $entry->getAttribute('gtPrimaryGTAccountUsername'))) || 
            (in_array("ms366", $entry->getAttribute('gtPrimaryGTAccountUsername')))
        ) {
            return true;
        }
        return false;
    }

    private function isObserver(Entry $entry): bool {
        if ($this->isAdmin($entry) || $this->isManager($entry)) {
            return true;
        }
        if (
            (in_array("staff@psdept 682:office und:office undergraduate admission", $entry->getAttribute('eduPersonScopedAffiliation')))
        ) {
            return true;
        }
        return false;
    }

    private function isCoordinator(Entry $entry): bool {
        if ($this->isAdmin($entry) || $this->isManager($entry)) {
            return true;
        }
        if (
            (in_array("staff@psdept 680:registrar:registrar's office", $entry->getAttribute('eduPersonScopedAffiliation')))
        ) {
            return true;
        }
        return false;
    }

    private function isManager(Entry $entry): bool {
        if ($this->isAdmin($entry)) {
            return true;
        }
        if (
            (in_array("celster3", $entry->getAttribute('gtPrimaryGTAccountUsername')))
        ) {
            return true;
        }
        return false;
    }

    private function isAdmin(Entry $entry): bool {
        if (
            (in_array("dbarbero3", $entry->getAttribute('gtPrimaryGTAccountUsername')))
        ) {
            return true;
        }
        return false;
    }

    public function getDevUserData(string $givenUsername): array
    {
        $userData = array();
        $userData['username'] = $givenUsername;
        $userData['org_id'] = $this->generateRandomID();
        $userData['display_name'] = $this->generateRandomString(5).' '.$this->generateRandomString(8);
        $userData['email'] = $this->generateRandomString(6).'@fake.gatech.edu';
        $userData['category'] = 'dev-user';
        $userData['status'] = 1;
        $userData['frozen'] = 0;
        $userData['loaded_from'] = 'lookup';
        $userData['roles'] = [User::ROLE_USER];
        return $userData;
    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function generateRandomID() {
        $characters = '123456789';
        $randomString = '';
        for ($i = 0; $i < 9; $i++) {
            $randomString .= $characters[random_int(0, 8)];
        }
        return $randomString;
    }

    private function getIndexZero(array $someArr): string {
        if (isset($someArr[0])) {
            return $someArr[0];
        }
        return '';
    }

		public function getRequesterType(array $requesterAttributes): string
		{
				if ($_ENV["APP_ENV"] === 'dev') {
						return 'TBD';
				}

				if ($this->isUndergraduateStudent($requesterAttributes['Attr'])) {
						return 'Student';
				}
				if ($this->isConfirmedUndergraduateApplicant($requesterAttributes['Attr'])) {
						return 'Confirmed Applicant';
				}
				if ($this->isAcceptedUndergraduateApplicant($requesterAttributes['Attr'])) {
						return 'Accepted Applicant';
				}
				if ($this->isUndergraduateApplicant($requesterAttributes['Attr'])) {
						return 'Applicant';
				}
				if ($this->isGraduateStudent($requesterAttributes['Attr'])) {
						return 'Graduate Student';
				}
				if ($this->isGraduateApplicant($requesterAttributes['Attr'])) {
						return 'Graduate Applicant';
				}
				if ($this->isFullTimeEmployee($requesterAttributes['Attr'])) {
						return 'Faculty/Staff';
				}
				return 'Unknown';
		}

		public function getRequesterAttributes(int $givenID): array
		{
				if ($_ENV["APP_ENV"] === 'dev') {
						return array(
							'Time' => date('Y-m-d H:i:s'),
							'GTID' => $givenID,
							'Attr' => array('tbd@ro', 'dev-env@db'),
						);
				}

				$ldap = Ldap::create('ext_ldap', ['connection_string' => $_ENV["LDAP_HOST"].':'.(int)$_ENV["LDAP_PORT"]]);
				$ldap->bind($_ENV["LDAP_DN"], $_ENV["LDAP_PW"]);
				$query = $ldap->query('ou=accounts,ou=gtaccounts,ou=departments,dc=gted,dc=gatech,dc=edu', "(&(gtGTID={$givenID}))");
				$result = $query->execute();

				$relevantAttributes = array(
					'credit-student@gt',
					'credit-applicant-accepted@gt',
					'credit-applicant-confirmed@gt',
					'credit-applicant@gt',
					'former-credit-student@gt',
					'full-time-employee@gt',
					'grad-applicant@gt',
					'graduate-student@gt',
					'undergrad-student@gt',
					'undergrad-applicant@gt'
				);

				$requesterAttributes = array();
				foreach ($relevantAttributes as $attribute) {
					if (in_array($attribute, $result[0]->getAttribute('eduPersonScopedAffiliation'))) {
						$requesterAttributes[] = $attribute;
					}
				}
				return array(
					'Time' => date('Y-m-d H:i:s'),
					'GTID' => $givenID,
					'Attr' => $requesterAttributes,
				);
		}

		private function isUndergraduateStudent(array $attributes): bool {
				if (
					(in_array("credit-student@gt", $attributes)) &&
					(in_array("undergrad-student@gt", $attributes))
				) {
						return true;
				}
				return false;
		}

		private function isConfirmedUndergraduateApplicant(array $attributes): bool {
				if (
					in_array("credit-applicant-confirmed@gt", $attributes) &&
					in_array("undergrad-applicant@gt", $attributes)
				) {
						return true;
				}
				return false;
		}

		private function isAcceptedUndergraduateApplicant(array $attributes): bool {
				if (
					in_array("credit-applicant-accepted@gt", $attributes) &&
					in_array("undergrad-applicant@gt", $attributes)
				) {
						return true;
				}
				return false;
		}

		private function isUndergraduateApplicant(array $attributes): bool {
				if (in_array("undergrad-applicant@gt", $attributes)) {
						return true;
				}
				return false;
		}

		private function isGraduateStudent(array $attributes): bool {
				if (
					(in_array("credit-student@gt", $attributes)) &&
					(in_array("graduate-student@gt", $attributes))
				) {
						return true;
				}
				return false;
		}

		private function isGraduateApplicant(array $attributes): bool {
				if (in_array("grad-applicant@gt", $attributes)) {
						return true;
				}
				return false;
		}

		private function isFullTimeEmployee(array $attributes): bool {
				if (in_array("full-time-employee@gt", $attributes)) {
						return true;
				}
				return false;
		}
}
