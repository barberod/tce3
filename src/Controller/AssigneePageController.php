<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Form\EvaluationAssignType;
use App\Form\EvaluationEvaluateType;
use App\Repository\EvaluationRepository;
use App\Service\EvaluationOptionsService;
use App\Service\EvaluationProcessingService;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('assignee')]
class AssigneePageController extends AbstractController
{
		private EntityManagerInterface $entityManager;
		private Security $security;

		public function __construct(
			EntityManagerInterface $entityManager,
			Security $security,
		) {
				$this->entityManager = $entityManager;
				$this->security = $security;
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
				$orderBy = (isset($_GET['orderby']) && (in_array($_GET['orderby'], ['updated', 'created']))) ? $_GET['orderby'] : null;
				$direction = (isset($_GET['direction']) && (in_array($_GET['direction'], ['asc', 'desc']))) ? $_GET['direction'] : null;
				$newDirection = (isset($_GET['direction']) && ($_GET['direction'] == 'asc')) ? 'desc' : 'asc';
				$reqAdm = (isset($_GET['reqadm']) && (in_array($_GET['reqadm'], ['yes', 'no']))) ? ucfirst($_GET['reqadm']) : null;

				$queryBuilder = $evaluationRepository->getQB(
					orderBy: $orderBy,
					direction: $direction,
					reqAdmin: $reqAdm,
					assignee: $this->security->getUser()
				);
				$adapter = new QueryAdapter($queryBuilder);
				$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

				return $this->render('evaluation/table.html.twig', [
					'context' => 'assignee',
					'page_title' => 'Evaluations',
					'prepend' => 'Evaluations',
					'pager' => $pagerfanta,
					'orderby' => $orderBy,
					'direction' => $direction,
					'direction_new' => $newDirection,
					'reqadm' => $reqAdm,
				]);
		}

		#[Route('/secure/assignee/evaluation/needs-attention', name: 'assignee_evaluation_table_needs_attention', methods: ['GET'])]
		public function assigneeEvaluationTableDept(EvaluationRepository $evaluationRepository): Response
		{
				$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
				$orderBy = (isset($_GET['orderby']) && (in_array($_GET['orderby'], ['updated', 'created']))) ? $_GET['orderby'] : null;
				$direction = (isset($_GET['direction']) && (in_array($_GET['direction'], ['asc', 'desc']))) ? $_GET['direction'] : null;
				$newDirection = (isset($_GET['direction']) && ($_GET['direction'] == 'asc')) ? 'desc' : 'asc';
				$reqAdm = (isset($_GET['reqadm']) && (in_array($_GET['reqadm'], ['yes', 'no']))) ? ucfirst($_GET['reqadm']) : null;

				$queryBuilder = $evaluationRepository->getQB(
					orderBy: $orderBy,
					direction: $direction,
					reqAdmin: $reqAdm,
					phase: 'Department',
					assignee: $this->security->getUser()
				);
				$adapter = new QueryAdapter($queryBuilder);
				$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

				return $this->render('evaluation/table.html.twig', [
					'context' => 'assignee',
					'page_title' => 'Needs Attention',
					'prepend' => 'Evaluations',
					'pager' => $pagerfanta,
					'orderby' => $orderBy,
					'direction' => $direction,
					'direction_new' => $newDirection,
					'reqadm' => $reqAdm,
					'assignee_flag' => 'Needs Attention',
				]);
		}

		#[Route('/secure/assignee/evaluation/history', name: 'assignee_evaluation_table_history', methods: ['GET'])]
		public function assigneeEvaluationTableComplete(EvaluationRepository
		$evaluationRepository): Response
		{
				$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
				$orderBy = (isset($_GET['orderby']) && (in_array($_GET['orderby'], ['updated', 'created']))) ? $_GET['orderby'] : null;
				$direction = (isset($_GET['direction']) && (in_array($_GET['direction'], ['asc', 'desc']))) ? $_GET['direction'] : null;
				$newDirection = (isset($_GET['direction']) && ($_GET['direction'] == 'asc')) ? 'desc' : 'asc';
				$reqAdm = (isset($_GET['reqadm']) && (in_array($_GET['reqadm'], ['yes', 'no']))) ? ucfirst($_GET['reqadm']) : null;

				$queryBuilder = $evaluationRepository->getQB(
					orderBy: $orderBy,
					direction: $direction,
					reqAdmin: $reqAdm,
					assignee: $this->security->getUser()
				);
				$queryBuilder->andWhere('e.phase != :phase')->setParameter('phase', 'Department');
				$adapter = new QueryAdapter($queryBuilder);
				$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

				return $this->render('evaluation/table.html.twig', [
					'context' => 'assignee',
					'page_title' => 'Evaluation History',
					'prepend' => 'Evaluations',
					'pager' => $pagerfanta,
					'orderby' => $orderBy,
					'direction' => $direction,
					'direction_new' => $newDirection,
					'reqadm' => $reqAdm,
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
		public function assigneeEvaluationEvaluateForm(Request $request, Evaluation $evaluation): Response
		{
				$form = $this->createForm(EvaluationEvaluateType::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->evaluateEvaluation($evaluation, $form->getData());
						return $this->redirectToRoute('assignee_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/evaluate.html.twig', [
					'context' => 'assignee',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Enter Equivalencies | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'evaluate',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/assignee/evaluation/{id}/forward', name: 'assignee_evaluation_forward_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'assignee+forward', 'evaluation' )]
		public function assigneeEvaluationForwardForm(Request $request, Evaluation $evaluation):
		Response
		{
				$form = $this->createForm(EvaluationAssignType::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->forwardEvaluation($evaluation, $form->getData());
						return $this->redirectToRoute('assignee_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/assign.html.twig', [
					'context' => 'assignee',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Forward to a Colleague | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'forward',
					'form' => $form->createView(),
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
