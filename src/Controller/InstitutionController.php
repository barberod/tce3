<?php

namespace App\Controller;

use App\Entity\Institution;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InstitutionController extends AbstractController
{
		private EntityManagerInterface $entityManager;

		public function __construct(
			EntityManagerInterface $entityManager
		) {
				$this->entityManager = $entityManager;
		}

		/**
		 * @Route("/get-institutions", name="ajax_get_institutions")
		 */
		public function getInstitutionsAction(Request $request): JsonResponse
		{
				$selectedState = $request->query->get('usState');

				$institutions = $this->entityManager
					->getRepository(Institution::class)
					->createQueryBuilder('i')
					->where('i.state = :selectedState')
					->setParameter('selectedState', $selectedState)
					->orderBy('i.name', 'ASC') // Order by name ascending
					->getQuery()
					->getResult();

				$options = [];
				foreach ($institutions as $institution) {
						$optionText = $institution->getName().' ('.$institution->getAddress().')';
						if (strlen($optionText) > 87) {
								$optionText = substr($optionText, 0, 87).'...';
						}
						$options[$optionText] =
							$institution->getId();
				}

				return new JsonResponse($options);
		}
}
