<?php

namespace App\Controller;

use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
		private EntityManagerInterface $entityManager;

		public function __construct(
			EntityManagerInterface $entityManager
		) {
				$this->entityManager = $entityManager;
		}

		/**
		 * @Route("/get-courses", name="ajax_get_courses")
		 */
		public function getCoursesAction(Request $request): JsonResponse
		{
				$selectedSubject = $request->query->get('subjectCode'); // Fetch

				$courses = $this->entityManager
					->getRepository(Course::class)
					->createQueryBuilder('c')
					->where('c.subjectCode = :selectedSubject')
					->setParameter('selectedSubject', $selectedSubject)
					->orderBy('c.courseNumber', 'ASC') // Order by courseNumber ascending
					->getQuery()
					->getResult();

				$options = [];
				foreach ($courses as $course) {
						$options[$course->getSubjectCode() . ' ' .
						$course->getCourseNumber()] = $course->getId();
				}

				return new JsonResponse($options);
		}
}
