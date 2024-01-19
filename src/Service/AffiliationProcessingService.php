<?php

namespace App\Service;

use App\Entity\Affiliation;
use App\Entity\Department;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EcPhp\CasBundle\Security\Core\User\CasUser;
use Symfony\Bundle\SecurityBundle\Security;

class AffiliationProcessingService
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
     */
    public function createAffiliation(array $formData, Department $department): void
    {
        $lookupService = new LookupService($this->entityManager);
        $lookupService->processUser($formData['username']);

        $person = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $formData['username']]);

        $affiliation = new Affiliation();
        $affiliation->setDepartment($department);
        $affiliation->setFacstaff($person);
        $affiliation->setLoadedFrom('');
        $this->entityManager->persist($affiliation);
        $this->entityManager->flush();
    }

    /**
     * Update
     */
    public function updateAffiliation(User $person): void
    {
        $lookupService = new LookupService($this->entityManager);
        $lookupService->processUser($person->getUsername());
    }

    /**
     * Delete
     */
    public function deleteAffiliation(User $person, Department $department): void
    {
        $affiliation = $this->entityManager->getRepository(Affiliation::class)->findOneBy(['facstaff' => $person, 'department' => $department]);
        $former = $this->entityManager->getRepository(Department::class)->findOneBy(['name' => 'Former']);
        $affiliation->setDepartment($former);
        $this->entityManager->persist($affiliation);
        $this->entityManager->flush();
    }
}