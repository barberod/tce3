<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Entity\User;
use App\Form\EvaluationAnnotateAsRequesterType;
use App\Form\EvaluationAnnotateType;
use App\Form\EvaluationAssignType;
use App\Form\EvaluationCreateType;
use App\Form\EvaluationEvaluateType;
use App\Form\EvaluationFinalizeType;
use App\Form\EvaluationFromCompleteToHoldType;
use App\Form\EvaluationFromDeptToR1Type;
use App\Form\EvaluationFromDeptToStudentType;
use App\Form\EvaluationFromR1ToStudentType;
use App\Form\EvaluationFromR2ToDeptType;
use App\Form\EvaluationFromR2ToStudentType;
use App\Form\EvaluationFromStudentToR1Type;
use App\Form\EvaluationHideType;
use App\Form\EvaluationHoldType;
use App\Form\EvaluationReassignType;
use App\Form\EvaluationResubmitType;
use App\Form\EvaluationSpotArticulateType;
use App\Repository\EvaluationRepository;
use App\Service\EvaluationFormDefaultsService;
use App\Service\EvaluationOptionsService;
use App\Service\EvaluationProcessingService;
use App\Service\LookupService;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('coordinator')]
class CoordinatorPageController extends AbstractController
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

		#[Route('/secure/coordinator', name: 'coordinator_home')]
		public function coordinator(): Response
		{
				return $this->render('page/homepage.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Transfer Credit Evaluation',
					'prepend' => 'Coordinator'
				]);
		}

		#[Route('/secure/coordinator/evaluation', name: 'coordinator_evaluation_table', methods: ['GET'])]
		public function coordinatorEvaluationTable(EvaluationRepository $evaluationRepository): Response
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
					'context' => 'coordinator',
					'page_title' => 'Evaluations',
					'prepend' => 'Evaluations',
					'pager' => $pagerfanta,
					'orderby' => $orderBy,
					'direction' => $direction,
					'direction_new' => $newDirection,
					'reqadm' => $reqAdm,
				]);
		}

		#[Route('/secure/coordinator/evaluation/student', name: 'coordinator_evaluation_table_student', methods: ['GET'])]
		public function coordinatorEvaluationTableStudent(EvaluationRepository
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
					'context' => 'coordinator',
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

		#[Route('/secure/coordinator/evaluation/r1', name: 'coordinator_evaluation_table_r1', methods: ['GET'])]
		public function coordinatorEvaluationTableR1(EvaluationRepository
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
					'context' => 'coordinator',
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

		#[Route('/secure/coordinator/evaluation/dept', name: 'coordinator_evaluation_table_dept', methods: ['GET'])]
		public function coordinatorEvaluationTableDept(EvaluationRepository
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
					'context' => 'coordinator',
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

		#[Route('/secure/coordinator/evaluation/r2', name: 'coordinator_evaluation_table_r2', methods: ['GET'])]
		public function coordinatorEvaluationTableR2(EvaluationRepository
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
					'context' => 'coordinator',
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

		#[Route('/secure/coordinator/evaluation/hold', name: 'coordinator_evaluation_table_hold', methods: ['GET'])]
		public function coordinatorEvaluationTableHold(EvaluationRepository
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
					'context' => 'coordinator',
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

		#[Route('/secure/coordinator/evaluation/complete', name: 'coordinator_evaluation_table_complete', methods: ['GET'])]
		public function coordinatorEvaluationTableComplete(EvaluationRepository
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
					'context' => 'coordinator',
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

		#[Route('/secure/coordinator/evaluation/requester', name: 'coordinator_evaluation_table_requester', methods: ['GET', 'POST'])]
		public function coordinatorEvaluationTableRequester(EvaluationRepository $evaluationRepository, SessionInterface $session): Response
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
					'context' => 'coordinator',
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

		#[Route('/secure/coordinator/evaluation/{id}', name: 'coordinator_evaluation_page', methods: ['GET'])]
		#[IsGranted('coordinator+read', 'evaluation')]
		public function coordinatorEvaluationPage(
			Evaluation $evaluation,
			EvaluationOptionsService $optionsService
		):
		Response {
				return $this->render('evaluation/page.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'options' => $optionsService->getOptions('coordinator', $evaluation),
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/update', name: 'coordinator_evaluation_update_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+update', 'evaluation' )]
		public function coordinatorEvaluationUpdateForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Edit Details | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'update'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/delete', name: 'coordinator_evaluation_delete_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+delete', 'evaluation' )]
		public function coordinatorEvaluationDeleteForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Delete | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'delete'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/annotate', name: 'coordinator_evaluation_annotate_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+annotate', 'evaluation' )]
		public function coordinatorEvaluationAnnotateForm(Request $request,
			Evaluation $evaluation): Response
		{
				$form = $this->createForm(EvaluationAnnotateType::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->annotateEvaluation($evaluation,
							$form->getData());
						return $this->redirectToRoute('coordinator_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/annotate.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Write a Note | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'annotate',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/annotate-as-requester', name: 'coordinator_evaluation_annotate_as_requester_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+annotate_as_requester', 'evaluation' )]
		public function coordinatorEvaluationAnnotateAsRequesterForm(Request $request, Evaluation $evaluation): Response
		{
				$form = $this->createForm(EvaluationAnnotateAsRequesterType::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->annotateAsRequesterEvaluation($evaluation, $form->getData());
						return $this->redirectToRoute('coordinator_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/annotate-as-requester.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Write a Note | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'annotate-as-requester',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/append', name: 'coordinator_evaluation_append_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+append', 'evaluation' )]
		public function coordinatorEvaluationAppendForm(Evaluation $evaluation):
		Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Upload a File | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'append'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/assign', name: 'coordinator_evaluation_assign_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+assign', 'evaluation' )]
		public function coordinatorEvaluationAssignForm(Request $request,
			Evaluation $evaluation): Response
		{
				$form = $this->createForm(EvaluationAssignType::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->assignEvaluation($evaluation, $form->getData());
						return $this->redirectToRoute('coordinator_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/assign.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Assign to Department | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'assign',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/evaluate', name: 'coordinator_evaluation_evaluate_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+evaluate', 'evaluation' )]
		public function coordinatorEvaluationEvaluateForm(Request $request, Evaluation $evaluation): Response
		{
				$form = $this->createForm(EvaluationEvaluateType::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->evaluateEvaluation($evaluation, $form->getData());
						return $this->redirectToRoute('coordinator_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/evaluate.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Enter Equivalencies | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'evaluate',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/example', name: 'coordinator_evaluation_example_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+example', 'evaluation' )]
		public function coordinatorEvaluationExampleForm(Evaluation $evaluation):
		Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Example | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'example'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/finalize', name: 'coordinator_evaluation_finalize_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+finalize', 'evaluation' )]
		public function coordinatorEvaluationFinalizeForm(Request $request, Evaluation $evaluation): Response
		{
				$form = $this->createForm(EvaluationFinalizeType::class, null, ['evaluation' => $evaluation]);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->finalizeEvaluation($evaluation, $form->getData());
						return $this->redirectToRoute('coordinator_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/finalize.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Finalize | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'finalize',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/forward', name: 'coordinator_evaluation_forward_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+forward', 'evaluation' )]
		public function coordinatorEvaluationForwardForm(Request $request, Evaluation $evaluation):
		Response
		{
				$form = $this->createForm(EvaluationAssignType::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->forwardEvaluation($evaluation, $form->getData());
						return $this->redirectToRoute('coordinator_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/assign.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Forward to a Colleague | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'forward',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/from-complete-to-hold', name: 'coordinator_evaluation_from_complete_to_hold_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+from_complete_to_hold', 'evaluation' )]
		public function coordinatorEvaluationFromCompleteToHoldForm(Request $request, Evaluation $evaluation):	Response
		{
				$form = $this->createForm(EvaluationFromCompleteToHoldType::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->fromCompleteToHoldEvaluation($evaluation, $form->getData());
						return $this->redirectToRoute('coordinator_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/from-complete-to-hold.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Hold | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-complete-to-hold',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/from-dept-to-r1', name: 'coordinator_evaluation_from_dept_to_r1_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+from_dept_to_r1', 'evaluation' )]
		public function coordinatorEvaluationFromDeptToR1Form(Request $request, Evaluation $evaluation):	Response
		{
				$form = $this->createForm(EvaluationFromDeptToR1Type::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->fromDeptToR1Evaluation($evaluation, $form->getData());
						return $this->redirectToRoute('coordinator_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/from-dept-to-r1.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Send to R1 | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-dept-to-r1',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/from-dept-to-student', name: 'coordinator_evaluation_from_dept_to_student_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+from_dept_to_student', 'evaluation' )]
		public function coordinatorEvaluationFromDeptToStudentForm(Request $request, Evaluation $evaluation):	Response
		{
				$form = $this->createForm(EvaluationFromDeptToStudentType::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->fromDeptToStudentEvaluation($evaluation, $form->getData());
						return $this->redirectToRoute('coordinator_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/from-dept-to-student.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Send to Student | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-dept-to-student',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/from-r1-to-student', name: 'coordinator_evaluation_from_r1_to_student_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+from_r1_to_student', 'evaluation' )]
		public function coordinatorEvaluationFromR1ToStudentForm(Request $request, Evaluation $evaluation):	Response
		{
				$form = $this->createForm(EvaluationFromR1ToStudentType::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->fromR1ToStudentEvaluation($evaluation, $form->getData());
						return $this->redirectToRoute('coordinator_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/from-r1-to-student.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Send to Student | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-r1-to-student',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/from-r2-to-dept', name: 'coordinator_evaluation_from_r2_to_dept_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+from_r2_to_dept', 'evaluation' )]
		public function coordinatorEvaluationFromR2ToDeptForm(Request $request, Evaluation $evaluation):	Response
		{
				$form = $this->createForm(EvaluationFromR2ToDeptType::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->fromR2ToDeptEvaluation($evaluation, $form->getData());
						return $this->redirectToRoute('coordinator_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/from-r2-to-dept.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Send to Department | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-r2-to-dept',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/from-r2-to-student', name: 'coordinator_evaluation_from_r2_to_student_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+from_r2_to_student', 'evaluation' )]
		public function coordinatorEvaluationFromR2ToStudentForm(Request $request, Evaluation $evaluation):	Response
		{
				$form = $this->createForm(EvaluationFromR2ToStudentType::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->fromR2ToStudentEvaluation
						($evaluation, $form->getData());
						return $this->redirectToRoute('coordinator_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/from-r2-to-student.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Send to Student | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-r2-to-student',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/from-student-to-r1', name: 'coordinator_evaluation_from_student_to_r1_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+from_student_to_r1', 'evaluation' )]
		public function coordinatorEvaluationFromStudentToR1Form(Request $request, Evaluation $evaluation):	Response
		{
				$form = $this->createForm(EvaluationFromStudentToR1Type::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->fromStudentToR1Evaluation($evaluation, $form->getData());
						return $this->redirectToRoute('coordinator_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/from-student-to-r1.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Send to R1 | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-student-to-r1',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/hide', name: 'coordinator_evaluation_hide_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+hide', 'evaluation' )]
		public function coordinatorEvaluationHideForm(Request $request, Evaluation $evaluation): Response
		{
				$form = $this->createForm(EvaluationHideType::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->hideEvaluation($evaluation, $form->getData());
						return $this->redirectToRoute('coordinator_evaluation_table_complete', [], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/hide.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Hide | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'hide',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/hold', name: 'coordinator_evaluation_hold_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+hold', 'evaluation' )]
		public function coordinatorEvaluationHoldForm(Request $request, Evaluation $evaluation): Response
		{
				$form = $this->createForm(EvaluationHoldType::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->holdEvaluation($evaluation, $form->getData());
						return $this->redirectToRoute('coordinator_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/hold.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Hold | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'hold',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/pass', name: 'coordinator_evaluation_pass_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+pass', 'evaluation' )]
		public function coordinatorEvaluationPassForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Pass | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'pass'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/reassign', name: 'coordinator_evaluation_reassign_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+reassign', 'evaluation' )]
		public function coordinatorEvaluationReassignForm(Request $request, Evaluation $evaluation): Response
		{
				$form = $this->createForm(EvaluationReassignType::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->reassignEvaluation($evaluation,
							$form->getData());
						return $this->redirectToRoute('coordinator_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/reassign.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Reassign | Evaluation #'
						.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'reassign',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/resubmit', name: 'coordinator_evaluation_resubmit_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+resubmit', 'evaluation' )]
		public function coordinatorEvaluationResubmitForm(Request $request, Evaluation $evaluation): Response
		{
				$form = $this->createForm(EvaluationResubmitType::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->resubmitEvaluation($evaluation,
							$form->getData());
						return $this->redirectToRoute('coordinator_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/resubmit.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Resubmit | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'resubmit',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/spot-articulate', name: 'coordinator_evaluation_spot_articulate_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'coordinator+spot_articulate', 'evaluation' )]
		public function coordinatorEvaluationSpotArticulateForm(Request $request,
			Evaluation $evaluation): Response
		{
				$form = $this->createForm(EvaluationSpotArticulateType::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->spotArticulateEvaluation($evaluation, $form->getData());
						return $this->redirectToRoute('coordinator_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/spot-articulate.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Spot Articulate | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'spot-articulate',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/coordinator/course', name: 'coordinator_course_table', methods: ['GET'])]
		public function coordinatorCourseTable(): Response
		{
				return $this->render('course/table.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Courses',
					'prepend' => 'Courses'
				]);
		}

		#[Route('/secure/coordinator/department', name: 'coordinator_department_table', methods: ['GET'])]
		public function coordinatorDepartmentTable(): Response
		{
				return $this->render('department/table.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Departments',
					'prepend' => 'Departments'
				]);
		}

		#[Route('/secure/coordinator/institution', name: 'coordinator_institution_table', methods: ['GET'])]
		public function coordinatorInstitutionTable(): Response
		{
				return $this->render('institution/table.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Institutions',
					'prepend' => 'Institutions'
				]);
		}
}
