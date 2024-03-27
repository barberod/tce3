<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Form\EvaluationAnnotateAsRequesterType;
use App\Form\EvaluationAppendAsRequesterType;
use App\Form\EvaluationCreateType;
use App\Form\EvaluationResubmitType;
use App\Form\EvaluationUpdateType;
use App\Repository\EvaluationRepository;
use App\Service\EvaluationFilesService;
use App\Service\EvaluationOptionsService;
use App\Service\EvaluationProcessingService;
use App\Service\EvaluationValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('requester')]
class RequesterPageController extends AbstractController
{
		private EntityManagerInterface $entityManager;
		private Security $security;
		private EvaluationFilesService $filesService;

		public function __construct(
			EntityManagerInterface $entityManager,
			Security $security,
			EvaluationFilesService $filesService
		) {
				$this->entityManager = $entityManager;
				$this->security = $security;
				$this->filesService = $filesService;
		}

		#[Route('/secure/requester', name: 'requester_home')]
		public function requester(): Response
		{
				return $this->render('page/homepage.html.twig', [
					'context' => 'requester',
					'page_title' => 'Transfer Credit Evaluation',
					'prepend' => 'Requester'
				]);
		}

		#[Route('/secure/requester/evaluation', name: 'requester_evaluation_table', methods: ['GET'])]
		public function requesterEvaluationTable(EvaluationRepository $evaluationRepository): Response
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
					requester: $this->security->getUser()
				);
				$adapter = new QueryAdapter($queryBuilder);
				$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

				return $this->render('evaluation/table.html.twig', [
					'context' => 'requester',
					'page_title' => 'My Evaluations',
					'prepend' => 'My Evaluations',
					'pager' => $pagerfanta,
					'orderby' => $orderBy,
					'direction' => $direction,
					'direction_new' => $newDirection,
					'reqadm' => $reqAdm,
				]);
		}

		#[Route('/secure/requester/evaluation/create', name: 'requester_evaluation_create_form', methods: ['GET', 'POST'])]
		public function requesterEvaluationCreateForm(Request $request): Response
		{
			$form = $this->createForm(EvaluationCreateType::class);
			$form->handleRequest($request);

			$validationResponse = [];
			if ($form->isSubmitted()) {
				$evaluationValidationService = new EvaluationValidationService();
				$validationResponse = $evaluationValidationService->validateCreateEvaluation($form->getData());
			}

			if ($form->isSubmitted() && $form->isValid()) {
				$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
				$evaluationProcessingService->createEvaluation($form->getData());
				return $this->redirectToRoute('requester_evaluation_table');
			}

			return $this->render('evaluation/form/create.html.twig', [
				'context' => 'requester',
				'page_title' => 'Create Evaluation',
				'prepend' => 'Create Evaluation',
				'form' => $form->createView(),
				'postData' => $form->getData(),
				'validation' => $validationResponse ?? [],
			]);
		}

		#[Route('/secure/requester/evaluation/{id}', name: 'requester_evaluation_page', methods: ['GET'])]
		#[IsGranted('requester+read', 'evaluation')]
		public function requesterEvaluationPage(
			Evaluation $evaluation,
			EvaluationOptionsService $optionsService
		): Response {
			return $this->render('evaluation/page.html.twig', [
				'context' => 'requester',
				'page_title' => 'Evaluation #'.$evaluation->getID(),
				'prepend' => 'Evaluation #'.$evaluation->getID(),
				'evaluation' => $evaluation,
				'id' => $evaluation->getID(),
				'uuid' => $evaluation->getID(),
				'options' => $optionsService->getOptions('requester', $evaluation),
				'files' => $this->filesService->getFileLocations($evaluation),
			]);
		}

		#[Route('/secure/requester/evaluation/{id}/update-as-requester', name: 'requester_evaluation_update_as_requester_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'requester+update_as_requester', 'evaluation' )]
		public function requesterEvaluationUpdateForm(Request $request, Evaluation $evaluation): Response
		{
			$form = $this->createForm(EvaluationUpdateType::class, null, ['evaluation' => $evaluation]);
			$form->handleRequest($request);
			if ($form->isSubmitted()) {
				$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
				$evaluationProcessingService->updateAsRequesterEvaluation($evaluation, $form->getData());
				return $this->redirectToRoute('requester_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
			}

			return $this->render('evaluation/form/update.html.twig', [
				'context' => 'requester',
				'page_title' => 'Evaluation #'.$evaluation->getID(),
				'prepend' => 'Edit Details | Evaluation #'.$evaluation->getID(),
				'evaluation' => $evaluation,
				'id' => $evaluation->getID(),
				'uuid' => $evaluation->getID(),
				'verb' => 'update-as-requester',
				'form' => $form->createView(),
			]);
		}

		#[Route('/secure/requester/evaluation/{id}/delete-as-requester', name: 'requester_evaluation_delete_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'requester+delete_as_requester', 'evaluation' )]
		public function requesterEvaluationDeleteForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'requester',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Delete | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'delete'
				]);
		}

		#[Route('/secure/requester/evaluation/{id}/annotate-as-requester', name: 'requester_evaluation_annotate_as_requester_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'requester+annotate_as_requester', 'evaluation' )]
		public function requesterEvaluationAnnotateAsRequesterForm(Request $request, Evaluation $evaluation): Response
		{
				$form = $this->createForm(EvaluationAnnotateAsRequesterType::class);
				$form->handleRequest($request);
				if ($form->isSubmitted()) {
						$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
						$evaluationProcessingService->annotateAsRequesterEvaluation($evaluation, $form->getData());
						return $this->redirectToRoute('requester_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
				}

				return $this->render('evaluation/form/annotate-as-requester.html.twig', [
					'context' => 'requester',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Write a Note | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'annotate-as-requester',
					'form' => $form->createView(),
				]);
		}

		#[Route('/secure/requester/evaluation/{id}/append-as-requester', name: 'requester_evaluation_append_as_requester_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'requester+append_as_requester', 'evaluation' )]
		public function requesterEvaluationAppendForm(Request $request, Evaluation $evaluation): Response
		{
			$form = $this->createForm(EvaluationAppendAsRequesterType::class);
			$form->handleRequest($request);
			if ($form->isSubmitted()) {
				$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
				$evaluationProcessingService->appendAsRequesterEvaluation($evaluation, $form->getData());
				return $this->redirectToRoute('requester_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
			}

			return $this->render('evaluation/form/append-as-requester.html.twig', [
				'context' => 'requester',
				'page_title' => 'Evaluation #'.$evaluation->getID(),
				'prepend' => 'Upload a File | Evaluation #'.$evaluation->getID(),
				'evaluation' => $evaluation,
				'id' => $evaluation->getID(),
				'uuid' => $evaluation->getID(),
				'verb' => 'append-as-requester',
				'form' => $form->createView(),
			]);
		}

		#[Route('/secure/requester/evaluation/{id}/resubmit', name: 'requester_evaluation_resubmit_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'requester+resubmit', 'evaluation' )]
		public function requesterEvaluationResubmitForm(Request $request, Evaluation $evaluation): Response
		{
			$form = $this->createForm(EvaluationResubmitType::class);
			$form->handleRequest($request);
			if ($form->isSubmitted()) {
				$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
				$evaluationProcessingService->resubmitEvaluation($evaluation,
					$form->getData());
				return $this->redirectToRoute('requester_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
			}

			return $this->render('evaluation/form/resubmit.html.twig', [
				'context' => 'requester',
				'page_title' => 'Evaluation #'.$evaluation->getID(),
				'prepend' => 'Resubmit | Evaluation #'.$evaluation->getID(),
				'evaluation' => $evaluation,
				'id' => $evaluation->getID(),
				'uuid' => $evaluation->getID(),
				'verb' => 'resubmit',
				'form' => $form->createView(),
			]);
		}

		#[Route('/secure/requester/course', name: 'requester_course_table', methods: ['GET'])]
		public function requesterCourseTable(): Response
		{
				return $this->render('course/table.html.twig', [
					'context' => 'requester',
					'page_title' => 'Courses',
					'prepend' => 'Courses'
				]);
		}

		#[Route('/secure/requester/department', name: 'requester_department_table', methods: ['GET'])]
		public function requesterDepartmentTable(): Response
		{
				return $this->render('department/table.html.twig', [
					'context' => 'requester',
					'page_title' => 'Departments',
					'prepend' => 'Departments'
				]);
		}

		#[Route('/secure/requester/institution', name: 'requester_institution_table', methods: ['GET'])]
		public function requesterInstitutionTable(): Response
		{
				return $this->render('institution/table.html.twig', [
					'context' => 'requester',
					'page_title' => 'Institutions',
					'prepend' => 'Institutions'
				]);
		}

		#[Route('/secure/requester/file/{id}/{subfolder}/{filename}', name: 'requester_file_download', methods: ['GET'])]
		#[IsGranted('requester+read', 'evaluation')]
		public function downloadFile(Evaluation $evaluation, string $subfolder, string $filename): Response
		{
				$filePath = $this->filesService->getFilePath($evaluation, $subfolder, $filename);
				if (!file_exists($filePath)) {
						throw $this->createNotFoundException('The file does not exist.');
				}
				$fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
				$response = new BinaryFileResponse($filePath);

				// Set content disposition based on file extension
				if (in_array($fileExtension, ['pdf', 'png', 'jpg', 'jpeg', 'gif'], true)) {
						$response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $filename);
				} else {
						$response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
				}

				$response->headers->set('Content-Type', $this->filesService->getMimeType($filePath));
				return $response;
		}
}
