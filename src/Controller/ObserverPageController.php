<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Entity\User;
use App\Repository\EvaluationRepository;
use App\Service\EvaluationFormDefaultsService;
use App\Service\EvaluationOptionsService;
use App\Service\LookupService;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('observer')]
class ObserverPageController extends AbstractController
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
				$orderBy = (isset($_GET['orderby']) && (in_array($_GET['orderby'], ['updated', 'created']))) ? $_GET['orderby'] : null;
				$direction = (isset($_GET['direction']) && (in_array($_GET['direction'], ['asc', 'desc']))) ? $_GET['direction'] : null;
				$newDirection = (isset($_GET['direction']) && ($_GET['direction'] == 'asc')) ? 'desc' : 'asc';
				$reqAdm = (isset($_GET['reqadm']) && (in_array($_GET['reqadm'], ['yes', 'no']))) ? ucfirst($_GET['reqadm']) : null;

				$queryBuilder = $evaluationRepository->getQB(
					orderBy: $orderBy,
					direction: $direction,
					reqAdmin: $reqAdm,
				);
				$adapter = new QueryAdapter($queryBuilder);
				$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

				return $this->render('evaluation/table.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluations',
					'prepend' => 'Evaluations',
					'pager' => $pagerfanta,
					'orderby' => $orderBy,
					'direction' => $direction,
					'direction_new' => $newDirection,
					'reqadm' => $reqAdm,
				]);
		}

		#[Route('/secure/observer/evaluation/student', name: 'observer_evaluation_table_student', methods: ['GET'])]
		public function observerEvaluationTableStudent(EvaluationRepository
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
					phase: 'Student'
				);
				$adapter = new QueryAdapter($queryBuilder);
				$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

				return $this->render('evaluation/table.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluations',
					'prepend' => 'Evaluations',
					'pager' => $pagerfanta,
					'orderby' => $orderBy,
					'direction' => $direction,
					'direction_new' => $newDirection,
					'reqadm' => $reqAdm,
					'phase' => 'Student',
				]);
		}

		#[Route('/secure/observer/evaluation/r1', name: 'observer_evaluation_table_r1', methods: ['GET'])]
		public function observerEvaluationTableR1(EvaluationRepository
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
					phase: 'Registrar 1'
				);
				$adapter = new QueryAdapter($queryBuilder);
				$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

				return $this->render('evaluation/table.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluations',
					'prepend' => 'Evaluations',
					'pager' => $pagerfanta,
					'orderby' => $orderBy,
					'direction' => $direction,
					'direction_new' => $newDirection,
					'reqadm' => $reqAdm,
					'phase' => 'Registrar 1',
				]);
		}

		#[Route('/secure/observer/evaluation/dept', name: 'observer_evaluation_table_dept', methods: ['GET'])]
		public function observerEvaluationTableDept(EvaluationRepository
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
					phase: 'Department'
				);
				$adapter = new QueryAdapter($queryBuilder);
				$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

				return $this->render('evaluation/table.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluations',
					'prepend' => 'Evaluations',
					'pager' => $pagerfanta,
					'orderby' => $orderBy,
					'direction' => $direction,
					'direction_new' => $newDirection,
					'reqadm' => $reqAdm,
					'phase' => 'Department',
				]);
		}

		#[Route('/secure/observer/evaluation/r2', name: 'observer_evaluation_table_r2', methods: ['GET'])]
		public function observerEvaluationTableR2(EvaluationRepository
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
					phase: 'Registrar 2'
				);
				$adapter = new QueryAdapter($queryBuilder);
				$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

				return $this->render('evaluation/table.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluations',
					'prepend' => 'Evaluations',
					'pager' => $pagerfanta,
					'orderby' => $orderBy,
					'direction' => $direction,
					'direction_new' => $newDirection,
					'reqadm' => $reqAdm,
					'phase' => 'Registrar 2',
				]);
		}

		#[Route('/secure/observer/evaluation/hold', name: 'observer_evaluation_table_hold', methods: ['GET'])]
		public function observerEvaluationTableHold(EvaluationRepository
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
					phase: 'Hold'
				);
				$adapter = new QueryAdapter($queryBuilder);
				$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

				return $this->render('evaluation/table.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluations',
					'prepend' => 'Evaluations',
					'pager' => $pagerfanta,
					'orderby' => $orderBy,
					'direction' => $direction,
					'direction_new' => $newDirection,
					'reqadm' => $reqAdm,
					'phase' => 'Hold',
				]);
		}

		#[Route('/secure/observer/evaluation/complete', name: 'observer_evaluation_table_complete', methods: ['GET'])]
		public function observerEvaluationTableComplete(EvaluationRepository
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
					phase: 'Complete'
				);
				$adapter = new QueryAdapter($queryBuilder);
				$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

				return $this->render('evaluation/table.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluations',
					'prepend' => 'Evaluations',
					'pager' => $pagerfanta,
					'orderby' => $orderBy,
					'direction' => $direction,
					'direction_new' => $newDirection,
					'reqadm' => $reqAdm,
					'phase' => 'Complete',
				]);
		}

		#[Route('/secure/observer/evaluation/requester', name: 'observer_evaluation_table_requester', methods: ['GET', 'POST'])]
		public function observerEvaluationTableRequester(EvaluationRepository $evaluationRepository, SessionInterface $session): Response
		{
				$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
				$orderBy = (isset($_GET['orderby']) && (in_array($_GET['orderby'], ['updated', 'created']))) ? $_GET['orderby'] : null;
				$direction = (isset($_GET['direction']) && (in_array($_GET['direction'], ['asc', 'desc']))) ? $_GET['direction'] : null;
				$newDirection = (isset($_GET['direction']) && ($_GET['direction'] == 'asc')) ? 'desc' : 'asc';
				$reqAdm = (isset($_GET['reqadm']) && (in_array($_GET['reqadm'], ['yes', 'no']))) ? ucfirst($_GET['reqadm']) : null;

				$id = (isset($_POST['id']) && (is_numeric($_POST['id'])) && ($_POST['id'] > 900000000) && ($_POST['id'] < 999999999)) ? $_POST['id'] : null;
				if (!is_null($id)) {
						$session->set('lookup_id', $id);
				} else {
						$id = $session->get('lookup_id');
				}

				$reset = isset($_POST['reset']) && ($_POST['reset'] == 1);
				if ($reset) {
						$session->remove('lookup_id');
						$id = null;
				}

				$requester = null;
				if (!is_null($id)) {
						$requester = $this->entityManager->getRepository(User::class)->findOneBy(['orgID' => $id]);
				}

				$requesterInfo = null;
				if (!is_null($requester)) {
						$lookup = new LookupService($this->entityManager);
						$requesterAttributes = $lookup->getRequesterAttributes($requester->getOrgID());
						$formDefaultsService = new EvaluationFormDefaultsService($this->entityManager);
						$requesterInfo = $formDefaultsService->styleRequesterAttributesAsText($requesterAttributes);

						$queryBuilder = $evaluationRepository->getQB(
							orderBy: $orderBy,
							direction: $direction,
							reqAdmin: $reqAdm,
							requester: $requester
						);
				} else {
						$queryBuilder = $evaluationRepository->getQB(bypass: true);
				}

				$adapter = new QueryAdapter($queryBuilder);
				$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

				return $this->render('evaluation/table.html.twig', [
					'context' => 'observer',
					'page_title' => 'GTID Lookup',
					'prepend' => 'GTID Lookup',
					'pager' => $pagerfanta,
					'orderby' => $orderBy,
					'direction' => $direction,
					'direction_new' => $newDirection,
					'reqadm' => $reqAdm,
					'requester_info' => $requesterInfo,
					'bonus_form' => 'id_lookup',
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
