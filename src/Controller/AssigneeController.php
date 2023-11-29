<?php

namespace App\Controller;

use App\Entity\Affiliation;
use App\Entity\Course;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssigneeController extends AbstractController
{
		private EntityManagerInterface $entityManager;

		public function __construct(
			EntityManagerInterface $entityManager
		) {
				$this->entityManager = $entityManager;
		}

		/**
		 * @Route("/get-assignees", name="ajax_get_assignees")
		 */
		public function getAssigneesAction(Request $request): JsonResponse
		{
				$department = $request->query->get('dept'); // Fetch

				$assignees = $this->entityManager
						->getRepository(User::class) // Assuming User is the entity representing faculty members
						->createQueryBuilder('u')
						->join('u.affiliations', 'a') // Assuming the property representing the affiliations in the User entity is named 'affiliations'
						->join('a.department', 'd') // Assuming the property representing the department in the Affiliation entity is named 'department'
						->where('d.id = :deptId')
						->setParameter('deptId', $department)
						->orderBy('u.displayName', 'ASC')
						->getQuery()
						->getResult();

				$options = [];
				foreach ($assignees as $assignee) {
						$options[$assignee->getDisplayName()] = $assignee->getUsername();
				}

				return new JsonResponse($options);
		}
}
