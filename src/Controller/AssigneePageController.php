<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Repository\EvaluationRepository;
use App\Service\EvaluationOptionsService;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('assignee')]
class AssigneePageController extends AbstractController
{
		private EntityManagerInterface $entityManager;

		public function __construct(
			EntityManagerInterface $entityManager
		) {
				$this->entityManager = $entityManager;
		}


		#[Route('/secure/assignee', name: 'assignee_home')]
		public function assignee(): Response
		{
				return $this->render('page/homepage.html.twig', [
					'context' => 'assignee',
					'page_title' => 'Transfer Credit Evaluation',
					'prepend' => 'Assignee'
				]);
		}

		#[Route('/secure/assignee/evaluation', name: 'assignee_evaluation_table', methods: ['GET'])]
		public function assigneeEvaluationTable(EvaluationRepository $evaluationRepository): Response
		{
				$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
				// $orderby = ($_GET['by'] == 'updated') ? 'updated' : 'created';
				// $direction = ($_GET['dir'] == 'asc') ? 'asc' : 'desc';

				$queryBuilder = $evaluationRepository->getQB();
				$adapter = new QueryAdapter($queryBuilder);
				$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

				return $this->render('evaluation/table.html.twig', [
					'context' => 'assignee',
					'page_title' => 'Evaluations',
					'prepend' => 'Evaluations',
					'pager' => $pagerfanta,
				]);
		}

		#[Route('/secure/assignee/evaluation/needs-attention', name: 'assignee_evaluation_table_needs_attention', methods: ['GET'])]
		public function assigneeEvaluationTableDept(EvaluationRepository
		$evaluationRepository, Security $security): Response
		{
				$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
				// $orderby = ($_GET['by'] == 'updated') ? 'updated' : 'created';
				// $direction = ($_GET['dir'] == 'asc') ? 'asc' : 'desc';

				$queryBuilder = $evaluationRepository->getQB();
				$queryBuilder
					->andWhere('e.phase = :phase')
					->andWhere('e.assignee = :assignee')
					->setParameter('phase', 'Department')
					->setParameter('assignee', $security->getUser());
				$adapter = new QueryAdapter($queryBuilder);
				$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

				return $this->render('evaluation/table.html.twig', [
					'context' => 'assignee',
					'page_title' => 'Needs Attention',
					'prepend' => 'Evaluations',
					'pager' => $pagerfanta,
					'assignee_flag' => 'Needs Attention',
				]);
		}

		#[Route('/secure/assignee/evaluation/history', name: 'assignee_evaluation_table_history', methods: ['GET'])]
		public function assigneeEvaluationTableComplete(EvaluationRepository
		$evaluationRepository, Security $security): Response
		{
				$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
				// $orderby = ($_GET['by'] == 'updated') ? 'updated' : 'created';
				// $direction = ($_GET['dir'] == 'asc') ? 'asc' : 'desc';

				$queryBuilder = $evaluationRepository->getQB();
				$queryBuilder
					->andWhere('e.phase != :phase')
					->andWhere('e.assignee = :assignee')
					->setParameter('phase', 'Department')
					->setParameter('assignee', $security->getUser());
				$adapter = new QueryAdapter($queryBuilder);
				$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

				return $this->render('evaluation/table.html.twig', [
					'context' => 'assignee',
					'page_title' => 'Evaluation History',
					'prepend' => 'Evaluations',
					'pager' => $pagerfanta,
					'assignee_flag' => 'History',
				]);
		}

		#[Route('/secure/assignee/evaluation/{id}', name: 'assignee_evaluation_page', methods: ['GET'])]
		#[IsGranted('assignee+read', 'evaluation')]
		public function assigneeEvaluationPage(
			Evaluation $evaluation,
			EvaluationOptionsService $optionsService
		):
		Response {
				return $this->render('evaluation/page.html.twig', [
					'context' => 'assignee',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'options' => $optionsService->getOptions('assignee', $evaluation),
				]);
		}

		#[Route('/secure/assignee/evaluation/{id}/annotate', name: 'assignee_evaluation_annotate_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'assignee+annotate', 'evaluation' )]
		public function assigneeEvaluationAnnotateForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'assignee',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Write a Note | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'annotate'
				]);
		}

		#[Route('/secure/assignee/evaluation/{id}/evaluate', name: 'assignee_evaluation_evaluate_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'assignee+evaluate', 'evaluation' )]
		public function assigneeEvaluationEvaluateForm(Evaluation $evaluation):
		Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'assignee',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Enter Equivalencies | Evaluation #'
						.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'evaluate'
				]);
		}

		#[Route('/secure/assignee/evaluation/{id}/forward', name: 'assignee_evaluation_forward_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'assignee+forward', 'evaluation' )]
		public function assigneeEvaluationForwardForm(Evaluation $evaluation):
		Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'assignee',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Forward to a Colleague | Evaluation #'
						.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'forward'
				]);
		}

		#[Route('/secure/assignee/evaluation/{id}/pass', name: 'assignee_evaluation_pass_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'assignee+pass', 'evaluation' )]
		public function assigneeEvaluationPassForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'assignee',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Pass | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'pass'
				]);
		}

		#[Route('/secure/assignee/course', name: 'assignee_course_table', methods: ['GET'])]
		public function assigneeCourseTable(): Response
		{
				return $this->render('course/table.html.twig', [
					'context' => 'assignee',
					'page_title' => 'Courses',
					'prepend' => 'Courses'
				]);
		}

		#[Route('/secure/assignee/department', name: 'assignee_department_table', methods: ['GET'])]
		public function assigneeDepartmentTable(): Response
		{
				return $this->render('department/table.html.twig', [
					'context' => 'assignee',
					'page_title' => 'Departments',
					'prepend' => 'Departments'
				]);
		}

		#[Route('/secure/assignee/institution', name: 'assignee_institution_table', methods: ['GET'])]
		public function assigneeInstitutionTable(): Response
		{
				return $this->render('institution/table.html.twig', [
					'context' => 'assignee',
					'page_title' => 'Institutions',
					'prepend' => 'Institutions'
				]);
		}
}
