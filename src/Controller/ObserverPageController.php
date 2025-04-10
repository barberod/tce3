<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Department;
use App\Entity\Evaluation;
use App\Entity\Institution;
use App\Entity\User;
use App\Form\EvaluationAnnotateType;
use App\Repository\AffiliationRepository;
use App\Repository\CourseRepository;
use App\Repository\DepartmentRepository;
use App\Repository\EvaluationRepository;
use App\Repository\InstitutionRepository;
use App\Service\EvaluationFilesService;
use App\Service\EvaluationFormDefaultsService;
use App\Service\EvaluationOptionsService;
use App\Service\EvaluationProcessingService;
use App\Service\FormOptionsService;
use App\Service\LookupService;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('observer')]
class ObserverPageController extends AbstractController
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
		public function observerEvaluationPage(Evaluation $evaluation,EvaluationOptionsService $optionsService): Response {
			return $this->render('evaluation/page.html.twig', [
				'context' => 'observer',
				'evaluation' => $evaluation,
				'files' => $this->filesService->getFileLocations($evaluation),
				'page_title' => 'Evaluation #'.$evaluation->getID(),
				'prepend' => 'Evaluation #'.$evaluation->getID(),
				'options' => $optionsService->getOptions('observer', $evaluation),
			]);
		}

		#[Route('/secure/observer/evaluation/{id}/annotate', name: 'observer_evaluation_annotate_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+annotate', 'evaluation' )]
		public function observerEvaluationAnnotateForm(Request $request, Evaluation $evaluation): Response
		{
			$form = $this->createForm(EvaluationAnnotateType::class);
			$form->handleRequest($request);
			if ($form->isSubmitted()) {
				$evaluationProcessingService = new EvaluationProcessingService($this->entityManager, $this->security);
				$evaluationProcessingService->annotateEvaluation($evaluation, $form->getData());
				return $this->redirectToRoute('observer_evaluation_page', ['id' => $evaluation->getID()], Response::HTTP_SEE_OTHER);
			}

			return $this->render('evaluation/form/annotate.html.twig', [
				'context' => 'observer',
				'evaluation' => $evaluation,
				'files' => $this->filesService->getFileLocations($evaluation),
				'page_title' => 'Evaluation #'.$evaluation->getID(),
				'prepend' => 'Write a Note | Evaluation #'.$evaluation->getID(),
				'verb' => 'annotate',
				'form' => $form->createView(),
			]);
		}

		#[Route('/secure/observer/course', name: 'observer_course_table', methods: ['GET'])]
		public function observerCourseTable(CourseRepository $courseRepository): Response
		{
			$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
			$orderBy = (isset($_GET['orderby']) && (in_array($_GET['orderby'], ['subjectCode', 'courseNumber']))) ? $_GET['orderby'] : null;
			$direction = (isset($_GET['direction']) && (in_array($_GET['direction'], ['asc', 'desc']))) ? $_GET['direction'] : null;
			$newDirection = (isset($_GET['direction']) && ($_GET['direction'] == 'asc')) ? 'desc' : 'asc';

			$service = new FormOptionsService($this->entityManager);
			$subj = (isset($_GET['subj']) && (in_array(strtoupper($_GET['subj']), $service->getSubjectCodeOptions()))) ? strtoupper($_GET['subj']) : null;

			$queryBuilder = $courseRepository->getQB(
				orderBy: $orderBy,
				direction: $direction,
				subjCode: $subj,
			);
			$adapter = new QueryAdapter($queryBuilder);
			$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

			return $this->render('course/table.html.twig', [
				'context' => 'observer',
				'page_title' => 'Courses',
				'prepend' => 'Courses',
				'pager' => $pagerfanta,
				'orderby' => $orderBy,
				'direction' => $direction,
				'direction_new' => $newDirection,
				'subj' => $subj,
				'subj_options' => $service->getSubjectCodeOptions(),
			]);
		}

		#[Route('/secure/observer/course/{id}', name: 'observer_course_page', methods: ['GET'])]
		public function observerCoursePage(Request $request, Course $course, EvaluationRepository $evaluationRepository): Response 
		{
			$course = $this->entityManager->getRepository(Course::class)->findOneBy(['id' => $request->attributes->get('id')]);

			$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
			$orderBy = (isset($_GET['orderby']) && (in_array($_GET['orderby'], ['updated', 'created']))) ? $_GET['orderby'] : null;
			$direction = (isset($_GET['direction']) && (in_array($_GET['direction'], ['asc', 'desc']))) ? $_GET['direction'] : null;
			$newDirection = (isset($_GET['direction']) && ($_GET['direction'] == 'asc')) ? 'desc' : 'asc';
			$reqAdm = (isset($_GET['reqadm']) && (in_array($_GET['reqadm'], ['yes', 'no']))) ? ucfirst($_GET['reqadm']) : null;

			$queryBuilder = $evaluationRepository->getEvaluationsByCourse(
				orderBy: $orderBy,
				direction: $direction,
				reqAdmin: $reqAdm,
				course: $course,
			);
			$adapter = new QueryAdapter($queryBuilder);
			$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

			return $this->render('course/page.html.twig', [
				'context' => 'observer',
				'page_title' => 'Course: '.$course->getSubjectCode().' '.$course->getCourseNumber(),
				'prepend' => 'Course: '.$course->getSubjectCode().' '.$course->getCourseNumber(),
				'pager' => $pagerfanta,
				'orderby' => $orderBy,
				'direction' => $direction,
				'direction_new' => $newDirection,
				'course' => $course,
				'reqadm' => $reqAdm,
			]);
		}

		#[Route('/secure/observer/department', name: 'observer_department_table', methods: ['GET'])]
		public function observerDepartmentTable(DepartmentRepository $departmentRepository): Response
		{
			$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
			$orderBy = (isset($_GET['orderby']) && (in_array($_GET['orderby'], ['id', 'name']))) ? $_GET['orderby'] : null;
			$direction = (isset($_GET['direction']) && (in_array($_GET['direction'], ['asc', 'desc']))) ? $_GET['direction'] : null;
			$newDirection = (isset($_GET['direction']) && ($_GET['direction'] == 'asc')) ? 'desc' : 'asc';

			$queryBuilder = $departmentRepository->getQB(
				orderBy: $orderBy,
				direction: $direction,
			);
			$adapter = new QueryAdapter($queryBuilder);
			$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

			return $this->render('department/table.html.twig', [
				'context' => 'observer',
				'page_title' => 'Departments',
				'prepend' => 'Departments',
				'pager' => $pagerfanta,
				'orderby' => $orderBy,
				'direction' => $direction,
				'direction_new' => $newDirection
			]);
		}

		#[Route('/secure/observer/department/{id}', name: 'observer_department_page', methods: ['GET'])]
		public function observerDepartmentPage(Department $department, AffiliationRepository $affiliationRepository): Response 
		{
			return $this->render('department/page.html.twig', [
				'context' => 'observer',
				'department' => $department,
				'page_title' => 'Department #'.$department->getID().' ('.$department->getName().')',
				'prepend' => 'Department #'.$department->getID().' ('.$department->getName().')',
				'persons' => $affiliationRepository->getPersons($department->getID()),
			]);
		}

		#[Route('/secure/observer/department/{id}/{username}', name: 'observer_department_assignee_page', methods: ['GET'])]
		public function observerDepartmentAssigneePage(Request $request): Response 
		{
			$evaluationRepository = $this->entityManager->getRepository(Evaluation::class);
			$department = $this->entityManager->getRepository(Department::class)->findOneBy(['id' => $request->attributes->get('id')]);
			$person = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $request->attributes->get('username')]);
			
			$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
			$orderBy = (isset($_GET['orderby']) && (in_array($_GET['orderby'], ['updated', 'created']))) ? $_GET['orderby'] : null;
			$direction = (isset($_GET['direction']) && (in_array($_GET['direction'], ['asc', 'desc']))) ? $_GET['direction'] : null;
			$newDirection = (isset($_GET['direction']) && ($_GET['direction'] == 'asc')) ? 'desc' : 'asc';

			$queryBuilder = $evaluationRepository->getQB(
				orderBy: $orderBy,
				direction: $direction,
				assignee: $person
			);
			$adapter = new QueryAdapter($queryBuilder);
			$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

			return $this->render('assignee/table.html.twig', [
				'context' => 'observer',
				'department' => $department,
				'person' => $person,
				'page_title' => 'Assignee: '.$person->getUsername().' ('.$person->getDisplayName().')',
				'prepend' => $person->getUsername(),
				'pager' => $pagerfanta,
				'orderby' => $orderBy,
				'direction' => $direction,
				'direction_new' => $newDirection
			]);
		}

		#[Route('/secure/observer/institution', name: 'observer_institution_table', methods: ['GET'])]
		public function observerInstitutionTable(InstitutionRepository $institutionRepository): Response
		{
			$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
			$orderBy = (isset($_GET['orderby']) && (in_array($_GET['orderby'], ['id', 'name']))) ? $_GET['orderby'] : null;
			$direction = (isset($_GET['direction']) && (in_array($_GET['direction'], ['asc', 'desc']))) ? $_GET['direction'] : null;
			$newDirection = (isset($_GET['direction']) && ($_GET['direction'] == 'asc')) ? 'desc' : 'asc';

			$service = new FormOptionsService($this->entityManager);
			$usState = (isset($_GET['usstate']) && (in_array(strtoupper($_GET['usstate']), $service->getUsStateAbbreviations()))) ? strtoupper($_GET['usstate']) : null;

			$queryBuilder = $institutionRepository->getQB(
				orderBy: $orderBy,
				direction: $direction,
				usState: $usState,
			);
			$adapter = new QueryAdapter($queryBuilder);
			$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

			return $this->render('institution/table.html.twig', [
				'context' => 'observer',
				'page_title' => 'Institutions',
				'prepend' => 'Institutions',
				'pager' => $pagerfanta,
				'orderby' => $orderBy,
				'direction' => $direction,
				'direction_new' => $newDirection,
				'usstate' => $usState,
				'us_state_options' => $service->getUsStateOptions(),
			]);
		}

		#[Route('/secure/observer/institution/{id}', name: 'observer_institution_page', methods: ['GET'])]
		public function observerInstitutionPage(Institution $institution): Response 
		{
			$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
			$orderBy = (isset($_GET['orderby']) && (in_array($_GET['orderby'], ['updated', 'created']))) ? $_GET['orderby'] : null;
			$direction = (isset($_GET['direction']) && (in_array($_GET['direction'], ['asc', 'desc']))) ? $_GET['direction'] : null;
			$newDirection = (isset($_GET['direction']) && ($_GET['direction'] == 'asc')) ? 'desc' : 'asc';

			$evaluationRepository = $this->entityManager->getRepository(Evaluation::class);
			$queryBuilder = $evaluationRepository->getQB(
				orderBy: $orderBy,
				direction: $direction,
				institution: $institution,
			);
			$adapter = new QueryAdapter($queryBuilder);
			$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);

			return $this->render('institution/page.html.twig', [
				'context' => 'observer',
				'institution' => $institution,
				'page_title' => 'Institution #'.$institution->getDapipID().' ('.$institution->getName().')',
				'prepend' => 'Institution #'.$institution->getDapipID(),
				'pager' => $pagerfanta,
				'orderby' => $orderBy,
				'direction' => $direction,
				'direction_new' => $newDirection,
			]);
		}

		#[Route('/secure/observer/file/{id}/{subfolder}/{filename}', name: 'observer_file_download', methods: ['GET'])]
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
