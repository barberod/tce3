<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Repository\EvaluationRepository;
use App\Service\EvaluationOptionsService;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('observer')]
class ObserverPageController extends AbstractController
{
		private EntityManagerInterface $entityManager;

		public function __construct(
			EntityManagerInterface $entityManager
		) {
				$this->entityManager = $entityManager;
		}


		#[Route('/secure/observer', name: 'observer_home')]
		public function observer(): Response
		{
				return $this->render('page/homepage.html.twig', [
					'context' => 'observer',
					'page_title' => 'Transfer Credit Evaluation',
					'prepend' => 'Observer'
				]);
		}

		#[Route('/secure/observer/evaluation', name: 'observer_evaluation_table', methods: ['GET'])]
		public function observerEvaluationTable(EvaluationRepository $evaluationRepository): Response
		{
				$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
				// $orderby = ($_GET['by'] == 'updated') ? 'updated' : 'created';
				// $direction = ($_GET['dir'] == 'asc') ? 'asc' : 'desc';

				$queryBuilder = $evaluationRepository->getQB();
				$adapter = new QueryAdapter($queryBuilder);
				$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

				return $this->render('evaluation/table.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluations',
					'prepend' => 'Evaluations',
					'pager' => $pagerfanta,
				]);
		}

		#[Route('/secure/observer/evaluation/{id}', name: 'observer_evaluation_page', methods: ['GET'])]
		#[IsGranted('observer+read', 'evaluation')]
		public function observerEvaluationPage(
			Evaluation $evaluation,
			EvaluationOptionsService $optionsService
		):
		Response {
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'options' => $optionsService->getOptions('observer', $evaluation),
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/annotate', name: 'observer_evaluation_annotate_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+annotate', 'evaluation' )]
		public function observerEvaluationAnnotateForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Write a Note | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'annotate'
				]);
		}

		#[Route('/secure/observer/course', name: 'observer_course_table', methods: ['GET'])]
		public function observerCourseTable(): Response
		{
				return $this->render('course/table.html.twig', [
					'context' => 'observer',
					'page_title' => 'Courses',
					'prepend' => 'Courses'
				]);
		}

		#[Route('/secure/observer/department', name: 'observer_department_table', methods: ['GET'])]
		public function observerDepartmentTable(): Response
		{
				return $this->render('department/table.html.twig', [
					'context' => 'observer',
					'page_title' => 'Departments',
					'prepend' => 'Departments'
				]);
		}

		#[Route('/secure/observer/institution', name: 'observer_institution_table', methods: ['GET'])]
		public function observerInstitutionTable(): Response
		{
				return $this->render('institution/table.html.twig', [
					'context' => 'observer',
					'page_title' => 'Institutions',
					'prepend' => 'Institutions'
				]);
		}
}
