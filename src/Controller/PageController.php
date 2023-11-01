<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Entity\User;
use App\Repository\EvaluationRepository;
use App\Service\EvaluationOptionsService;
use Doctrine\ORM\EntityManagerInterface;
use EcPhp\CasBundle\Security\Core\User\CasUser;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PageController extends AbstractController
{
		private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
				$this->entityManager = $entityManager;
		}

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('page/homepage.html.twig', [
            'page_title' => 'Transfer Credit Evaluation'
        ]);
    }

		#[Route('/my', name: 'redirect_to_secure_page')]
		public function redirectToSecurePage(Security $security): RedirectResponse
		{
				if ($security->getUser() instanceof User) {
						$roles = $security->getUser()->attributes()['profile']['roles'];
				} elseif ($security->getUser() instanceof CasUser) {
						$roles = $security->getUser()->getAttributes()['profile']['roles'];
				} else {
						$roles = [];
				}

				if (in_array('ROLE_COORDINATOR', $roles)) {
						return $this->redirectToRoute('coordinator_home');
				} elseif (in_array('ROLE_OBSERVER', $roles)){
						return $this->redirectToRoute('observer_home');
				} elseif (in_array('ROLE_ASSIGNEE', $roles)){
						return $this->redirectToRoute('assignee_home');
				} elseif (in_array('ROLE_REQUESTER', $roles)){
						return $this->redirectToRoute('requester_home');
				}
				return $this->redirectToRoute('roles');
		}

    #[Route('/secure', name: 'roles')]
    public function roles(): Response
    {
        return $this->render('page/roles.html.twig', [
            'page_title' => 'Transfer Credit Evaluation',
            'prepend' => 'Roles'
        ]);
    }

    #[Route('/secure/manager', name: 'manager_home')]
    public function manager(): Response
    {
        return $this->render('page/homepage.html.twig', [
            'context' => 'manager',
            'page_title' => 'Transfer Credit Evaluation',
            'prepend' => 'Manager'
        ]);
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

    #[Route('/secure/coordinator/evaluation',
			name: 'coordinator_evaluation_table', methods: ['GET'])]
    public function coordinatorEvaluationTable(EvaluationRepository $evaluationRepository): Response
    {
        $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
        // $orderby = ($_GET['by'] == 'updated') ? 'updated' : 'created';
        // $direction = ($_GET['dir'] == 'asc') ? 'asc' : 'desc';

        $queryBuilder = $evaluationRepository->getQB();
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $page, 30);
        
        return $this->render('evaluation/table.html.twig', [
            'context' => 'coordinator',
            'page_title' => 'Evaluations',
            'prepend' => 'Evaluations',
            'pager' => $pagerfanta,
        ]);
    }

		#[Route('/secure/coordinator/evaluation/{id}',
			name: 'coordinator_evaluation_page', methods: ['GET'])]
		#[IsGranted('READ', 'evaluation')]
		public function coordinatorEvaluationPage(
			Evaluation $evaluation,
			Security $security,
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
					'options' => $optionsService->getOptions(
						'coordinator',
						$evaluation,
						$security->getUser()
					),
				]);
		}

    #[Route('/secure/coordinator/evaluation/{id}/update',
			name: 'coordinator_evaluation_update_form', methods: ['GET', 'POST'])]
		#[IsGranted('UPDATE', 'evaluation')]
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

		#[Route('/secure/coordinator/evaluation/{id}/delete',
			name: 'coordinator_evaluation_delete_form', methods: ['GET', 'POST'])]
		#[IsGranted('DELETE', 'evaluation')]
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

		#[Route('/secure/coordinator/evaluation/{id}/annotate',
			name: 'coordinator_evaluation_annotate_form', methods: ['GET', 'POST'])]
		#[IsGranted('ANNOTATE', 'evaluation')]
		public function coordinatorEvaluationAnnotateForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Write a Note | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'annotate'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/annotate-as-requester',
			name: 'coordinator_evaluation_annotate_as_requester_form', methods: ['GET', 'POST'])]
		#[IsGranted('ANNOTATE_AS_REQUESTER', 'evaluation')]
		public function coordinatorEvaluationAnnotateAsRequesterForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
						'context' => 'coordinator',
						'page_title' => 'Evaluation #'.$evaluation->getID(),
						'prepend' => 'Write a Note | Evaluation #'.$evaluation->getID(),
						'evaluation' => $evaluation,
						'id' => $evaluation->getID(),
						'uuid' => $evaluation->getID(),
						'verb' => 'annotate-as-requester'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/append',
			name: 'coordinator_evaluation_append_form', methods: ['GET', 'POST'])]
		#[IsGranted('APPEND', 'evaluation')]
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

		#[Route('/secure/coordinator/evaluation/{id}/assign',
			name: 'coordinator_evaluation_assign_form', methods: ['GET', 'POST'])]
		#[IsGranted('ASSIGN', 'evaluation')]
		public function coordinatorEvaluationAssignForm(Evaluation $evaluation):
		Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Assign to Department | Evaluation #'
						.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'assign'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/evaluate',
			name: 'coordinator_evaluation_evaluate_form', methods: ['GET', 'POST'])]
		#[IsGranted('EVALUATE', 'evaluation')]
		public function coordinatorEvaluationEvaluateForm(Evaluation $evaluation):
		Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Enter Equivalencies | Evaluation #'
						.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'evaluate'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/example',
			name: 'coordinator_evaluation_example_form', methods: ['GET', 'POST'])]
		#[IsGranted('EXAMPLE', 'evaluation')]
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

		#[Route('/secure/coordinator/evaluation/{id}/finalize',
			name: 'coordinator_evaluation_finalize_form', methods: ['GET', 'POST'])]
		#[IsGranted('FINALIZE', 'evaluation')]
		public function coordinatorEvaluationFinalizeForm(Evaluation $evaluation):
		Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Finalize | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'finalize'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/forward',
			name: 'coordinator_evaluation_forward_form', methods: ['GET', 'POST'])]
		#[IsGranted('FORWARD', 'evaluation')]
		public function coordinatorEvaluationForwardForm(Evaluation $evaluation):
		Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Forward to a Colleague | Evaluation #'
						.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'forward'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/from-complete-to-hold',
			name: 'coordinator_evaluation_from_complete_to_hold_form', methods: ['GET', 'POST'])]
		#[IsGranted('FROM_COMPLETE_TO_HOLD', 'evaluation')]
		public function coordinatorEvaluationFromCompleteToHoldForm(Evaluation $evaluation):	Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Hold | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-complete-to-hold'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/from-dept-to-r1',
			name: 'coordinator_evaluation_from_dept_to_r1_form', methods: ['GET', 'POST'])]
		#[IsGranted('FROM_DEPT_TO_R1', 'evaluation')]
		public function coordinatorEvaluationFromDeptToR1Form(Evaluation $evaluation):	Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Send to R1 | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-dept-to-r1'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/from-dept-to-student', name:
			'coordinator_evaluation_from_dept_to_student_form', methods: ['GET', 'POST'])]
		#[IsGranted('FROM_DEPT_TO_STUDENT', 'evaluation')]
		public function coordinatorEvaluationFromDeptToStudentForm(Evaluation $evaluation):	Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Send to Student | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-dept-to-student'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/from-r1-to-student', name:
			'coordinator_evaluation_from_r1_to_student_form', methods: ['GET', 'POST'])]
		#[IsGranted('FROM_R1_TO_STUDENT', 'evaluation')]
		public function coordinatorEvaluationFromR1ToStudentForm(Evaluation $evaluation):	Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Send to Student | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-r1-to-student'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/from-r2-to-dept', name:
			'coordinator_evaluation_from_r2_to_dept_form', methods: ['GET', 'POST'])]
		#[IsGranted('FROM_R2_TO_DEPT', 'evaluation')]
		public function coordinatorEvaluationFromR2ToDeptForm(Evaluation $evaluation):	Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Send to Department | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-r2-to-dept'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/from-r2-to-student', name:
			'coordinator_evaluation_from_r2_to_student_form', methods: ['GET', 'POST'])]
		#[IsGranted('FROM_R2_TO_STUDENT', 'evaluation')]
		public function coordinatorEvaluationFromR2ToStudentForm(Evaluation $evaluation):	Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Send to Student | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-r2-to-student'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/from-student-to-r1', name:
			'coordinator_evaluation_from_student_to_r1_form', methods: ['GET', 'POST'])]
		#[IsGranted('FROM_STUDENT_TO_R1', 'evaluation')]
		public function coordinatorEvaluationFromStudentToR1Form(Evaluation $evaluation):	Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Send to R1 | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-student-to-r1'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/hide',
			name: 'coordinator_evaluation_hide_form', methods: ['GET', 'POST'])]
		#[IsGranted('HIDE', 'evaluation')]
		public function coordinatorEvaluationHideForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'coordinator',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Hide | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'hide'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/hold',
			name: 'coordinator_evaluation_hold_form', methods: ['GET', 'POST'])]
		#[IsGranted('HOLD', 'evaluation')]
		public function coordinatorEvaluationHoldForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
						'context' => 'coordinator',
						'page_title' => 'Evaluation #'.$evaluation->getID(),
						'prepend' => 'Hold | Evaluation #'.$evaluation->getID(),
						'evaluation' => $evaluation,
						'id' => $evaluation->getID(),
						'uuid' => $evaluation->getID(),
						'verb' => 'hold'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/pass',
			name: 'coordinator_evaluation_pass_form', methods: ['GET', 'POST'])]
		#[IsGranted('PASS', 'evaluation')]
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

		#[Route('/secure/coordinator/evaluation/{id}/reassign',
			name: 'coordinator_evaluation_reassign_form', methods: ['GET', 'POST'])]
		#[IsGranted('REASSIGN', 'evaluation')]
		public function coordinatorEvaluationReassignForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
						'context' => 'coordinator',
						'page_title' => 'Evaluation #'.$evaluation->getID(),
						'prepend' => 'Reassign to a Department | Evaluation #'.$evaluation->getID(),
						'evaluation' => $evaluation,
						'id' => $evaluation->getID(),
						'uuid' => $evaluation->getID(),
						'verb' => 'reassign'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/resubmit',
			name: 'coordinator_evaluation_resubmit_form', methods: ['GET', 'POST'])]
		#[IsGranted('RESUBMIT', 'evaluation')]
		public function coordinatorEvaluationResubmitForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
						'context' => 'coordinator',
						'page_title' => 'Evaluation #'.$evaluation->getID(),
						'prepend' => 'Resubmit | Evaluation #'.$evaluation->getID(),
						'evaluation' => $evaluation,
						'id' => $evaluation->getID(),
						'uuid' => $evaluation->getID(),
						'verb' => 'resubmit'
				]);
		}

		#[Route('/secure/coordinator/evaluation/{id}/spot-articulate',
			name: 'coordinator_evaluation_spot_articulate_form', methods: ['GET', 'POST'])]
		#[IsGranted('SPOT_ARTICULATE', 'evaluation')]
		public function coordinatorEvaluationSpotArticulateForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
						'context' => 'coordinator',
						'page_title' => 'Evaluation #'.$evaluation->getID(),
						'prepend' => 'Spot Articulate | Evaluation #'.$evaluation->getID(),
						'evaluation' => $evaluation,
						'id' => $evaluation->getID(),
						'uuid' => $evaluation->getID(),
						'verb' => 'spot-articulate'
				]);
		}

    #[Route('/secure/coordinator/course', name: 'coordinator_course_table')]
    public function coordinatorCourseTable(): Response
    {
        return $this->render('course/table.html.twig', [
            'context' => 'coordinator',
            'page_title' => 'Courses',
            'prepend' => 'Courses'
        ]);
    }

    #[Route('/secure/coordinator/department', name: 'coordinator_department_table')]
    public function coordinatorDepartmentTable(): Response
    {
        return $this->render('department/table.html.twig', [
            'context' => 'coordinator',
            'page_title' => 'Departments',
            'prepend' => 'Departments'
        ]);
    }

    #[Route('/secure/coordinator/institution', name: 'coordinator_institution_table')]
    public function coordinatorInstitutionTable(): Response
    {
        return $this->render('institution/table.html.twig', [
            'context' => 'coordinator',
            'page_title' => 'Institutions',
            'prepend' => 'Institutions'
        ]);
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

    #[Route('/secure/observer/evaluation', name: 'observer_evaluation_table')]
    public function observerEvaluationTable(): Response
    {
        return $this->render('evaluation/table.html.twig', [
            'context' => 'observer',
            'page_title' => 'Evaluations',
            'prepend' => 'Evaluations'
        ]);
    }

    #[Route('/secure/observer/evaluation/{uuid}', name: 'observer_evaluation_page')]
    public function observerEvaluationPage(string $uuid): Response
    {
        return $this->render('evaluation/page.html.twig', [
            'context' => 'observer',
            'page_title' => 'Evaluation #'.$uuid,
            'prepend' => 'Evaluation #'.$uuid,
            'uuid' => $uuid
        ]);
    }

    #[Route('/secure/observer/evaluation/{uuid}/{verb}', name: 'observer_evaluation_form')]
    public function observerEvaluationForm(string $uuid, string $verb): Response
    {
        return $this->render('evaluation/page.html.twig', [
            'context' => 'observer',
            'page_title' => 'Evaluation #'.$uuid,
            'prepend' => 'Evaluation #'.$uuid,
            'uuid' => $uuid,
            'verb' => $verb
        ]);
    }

    #[Route('/secure/observer/course', name: 'observer_course_table')]
    public function observerCourseTable(): Response
    {
        return $this->render('course/table.html.twig', [
            'context' => 'observer',
            'page_title' => 'Courses',
            'prepend' => 'Courses'
        ]);
    }

    #[Route('/secure/observer/department', name: 'observer_department_table')]
    public function observerDepartmentTable(): Response
    {
        return $this->render('department/table.html.twig', [
            'context' => 'observer',
            'page_title' => 'Departments',
            'prepend' => 'Departments'
        ]);
    }

    #[Route('/secure/observer/institution', name: 'observer_institution_table')]
    public function observerInstitutionTable(): Response
    {
        return $this->render('institution/table.html.twig', [
            'context' => 'observer',
            'page_title' => 'Institutions',
            'prepend' => 'Institutions'
        ]);
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

    #[Route('/secure/assignee/evaluation', name: 'assignee_evaluation_table')]
    public function assigneeEvaluationTable(): Response
    {
        return $this->render('evaluation/table.html.twig', [
            'context' => 'assignee',
            'page_title' => 'Evaluations',
            'prepend' => 'Evaluations'
        ]);
    }

    #[Route('/secure/assignee/evaluation/{uuid}', name: 'assignee_evaluation_page')]
    public function assigneeEvaluationPage(string $uuid): Response
    {
        return $this->render('evaluation/page.html.twig', [
            'context' => 'assignee',
            'page_title' => 'Evaluation #'.$uuid,
            'prepend' => 'Evaluation #'.$uuid,
            'uuid' => $uuid
        ]);
    }

    #[Route('/secure/assignee/evaluation/{uuid}/{verb}', name: 'assignee_evaluation_form')]
    public function assigneeEvaluationForm(string $uuid, string $verb): Response
    {
        return $this->render('evaluation/page.html.twig', [
            'context' => 'assignee',
            'page_title' => 'Evaluation #'.$uuid,
            'prepend' => 'Evaluation #'.$uuid,
            'uuid' => $uuid,
            'verb' => $verb
        ]);
    }

    #[Route('/secure/assignee/course', name: 'assignee_course_table')]
    public function assigneeCourseTable(): Response
    {
        return $this->render('course/table.html.twig', [
            'context' => 'assignee',
            'page_title' => 'Courses',
            'prepend' => 'Courses'
        ]);
    }

    #[Route('/secure/assignee/department', name: 'assignee_department_table')]
    public function assigneeDepartmentTable(): Response
    {
        return $this->render('department/table.html.twig', [
            'context' => 'assignee',
            'page_title' => 'Departments',
            'prepend' => 'Departments'
        ]);
    }

    #[Route('/secure/assignee/institution', name: 'assignee_institution_table')]
    public function assigneeInstitutionTable(): Response
    {
        return $this->render('institution/table.html.twig', [
            'context' => 'assignee',
            'page_title' => 'Institutions',
            'prepend' => 'Institutions'
        ]);
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

    #[Route('/secure/requester/evaluation', name: 'requester_evaluation_table')]
    public function requesterEvaluationTable(): Response
    {
        return $this->render('evaluation/table.html.twig', [
            'context' => 'requester',
            'page_title' => 'Evaluations',
            'prepend' => 'Evaluations'
        ]);
    }

    #[Route('/secure/requester/evaluation/{uuid}', name: 'requester_evaluation_page')]
    public function requesterEvaluationPage(string $uuid): Response
    {
        return $this->render('evaluation/page.html.twig', [
            'context' => 'requester',
            'page_title' => 'Evaluation #'.$uuid,
            'prepend' => 'Evaluation #'.$uuid,
            'uuid' => $uuid
        ]);
    }

    #[Route('/secure/requester/evaluation/{uuid}/{verb}', name: 'requester_evaluation_form')]
    public function requesterEvaluationForm(string $uuid, string $verb): Response
    {
        return $this->render('evaluation/page.html.twig', [
            'context' => 'requester',
            'page_title' => 'Evaluation #'.$uuid,
            'prepend' => 'Evaluation #'.$uuid,
            'uuid' => $uuid,
            'verb' => $verb
        ]);
    }



    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        $target = urlencode($this->getParameter('cas_login_target'));
        $url = $this->getParameter('cas_host') . ((($this->getParameter('cas_port')!=80) || ($this->getParameter('cas_port')!=443)) ? ":".$this->getParameter('cas_port') : "") . $this->getParameter('cas_path') . '/login?service=';
        return $this->redirect($url . $target);
    }
 
    #[Route('/logout', name: 'logout')]
    public function logout(Security $security): Response
    {
        session_destroy();
        if ($_ENV['APP_ENV'] === 'dev') {
            $security->logout();
            return $this->redirect('http://localhost:8000');
        }  
        $target = urlencode($this->getParameter('cas_logout_target'));
        $url = $this->getParameter('cas_host') . ((($this->getParameter('cas_port')!=80) || ($this->getParameter('cas_port')!=443)) ? ":".$this->getParameter('cas_port') : "") . $this->getParameter('cas_path') . '/logout?service=';
        return $this->redirect($url . $target);
    }

}
