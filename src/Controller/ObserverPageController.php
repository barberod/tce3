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

		#[Route('/secure/observer/evaluation/{id}/update', name: 'observer_evaluation_update_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+update', 'evaluation' )]
		public function observerEvaluationUpdateForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Edit Details | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'update'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/delete', name: 'observer_evaluation_delete_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+delete', 'evaluation' )]
		public function observerEvaluationDeleteForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Delete | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'delete'
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

		#[Route('/secure/observer/evaluation/{id}/annotate-as-requester', name: 'observer_evaluation_annotate_as_requester_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+annotate_as_requester', 'evaluation' )]
		public function observerEvaluationAnnotateAsRequesterForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Write a Note | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'annotate-as-requester'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/append', name: 'observer_evaluation_append_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+append', 'evaluation' )]
		public function observerEvaluationAppendForm(Evaluation $evaluation):
		Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Upload a File | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'append'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/assign', name: 'observer_evaluation_assign_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+assign', 'evaluation' )]
		public function observerEvaluationAssignForm(Evaluation $evaluation):
		Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Assign to Department | Evaluation #'
						.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'assign'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/evaluate', name: 'observer_evaluation_evaluate_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+evaluate', 'evaluation' )]
		public function observerEvaluationEvaluateForm(Evaluation $evaluation):
		Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Enter Equivalencies | Evaluation #'
						.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'evaluate'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/example', name: 'observer_evaluation_example_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+example', 'evaluation' )]
		public function observerEvaluationExampleForm(Evaluation $evaluation):
		Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Example | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'example'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/finalize', name: 'observer_evaluation_finalize_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+finalize', 'evaluation' )]
		public function observerEvaluationFinalizeForm(Evaluation $evaluation):
		Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Finalize | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'finalize'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/forward', name: 'observer_evaluation_forward_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+forward', 'evaluation' )]
		public function observerEvaluationForwardForm(Evaluation $evaluation):
		Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Forward to a Colleague | Evaluation #'
						.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'forward'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/from-complete-to-hold', name: 'observer_evaluation_from_complete_to_hold_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+from_complete_to_hold', 'evaluation' )]
		public function observerEvaluationFromCompleteToHoldForm(Evaluation $evaluation):	Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Hold | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-complete-to-hold'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/from-dept-to-r1', name: 'observer_evaluation_from_dept_to_r1_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+from_dept_to_r1', 'evaluation' )]
		public function observerEvaluationFromDeptToR1Form(Evaluation $evaluation):	Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Send to R1 | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-dept-to-r1'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/from-dept-to-student', name: 'observer_evaluation_from_dept_to_student_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+from_dept_to_student', 'evaluation' )]
		public function observerEvaluationFromDeptToStudentForm(Evaluation $evaluation):	Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Send to Student | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-dept-to-student'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/from-r1-to-student', name: 'observer_evaluation_from_r1_to_student_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+from_r1_to_student', 'evaluation' )]
		public function observerEvaluationFromR1ToStudentForm(Evaluation $evaluation):	Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Send to Student | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-r1-to-student'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/from-r2-to-dept', name: 'observer_evaluation_from_r2_to_dept_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+from_r2_to_dept', 'evaluation' )]
		public function observerEvaluationFromR2ToDeptForm(Evaluation $evaluation):	Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Send to Department | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-r2-to-dept'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/from-r2-to-student', name: 'observer_evaluation_from_r2_to_student_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+from_r2_to_student', 'evaluation' )]
		public function observerEvaluationFromR2ToStudentForm(Evaluation $evaluation):	Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Send to Student | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-r2-to-student'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/from-student-to-r1', name: 'observer_evaluation_from_student_to_r1_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+from_student_to_r1', 'evaluation' )]
		public function observerEvaluationFromStudentToR1Form(Evaluation $evaluation):	Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Send to R1 | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'from-student-to-r1'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/hide', name: 'observer_evaluation_hide_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+hide', 'evaluation' )]
		public function observerEvaluationHideForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Hide | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'hide'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/hold', name: 'observer_evaluation_hold_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+hold', 'evaluation' )]
		public function observerEvaluationHoldForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Hold | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'hold'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/pass', name: 'observer_evaluation_pass_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+pass', 'evaluation' )]
		public function observerEvaluationPassForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Pass | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'pass'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/reassign', name: 'observer_evaluation_reassign_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+reassign', 'evaluation' )]
		public function observerEvaluationReassignForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Reassign to a Department | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'reassign'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/resubmit', name: 'observer_evaluation_resubmit_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+resubmit', 'evaluation' )]
		public function observerEvaluationResubmitForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Resubmit | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'resubmit'
				]);
		}

		#[Route('/secure/observer/evaluation/{id}/spot-articulate', name: 'observer_evaluation_spot_articulate_form', methods: ['GET', 'POST'])]
		#[IsGranted( 'observer+spot_articulate', 'evaluation' )]
		public function observerEvaluationSpotArticulateForm(Evaluation $evaluation): Response
		{
				return $this->render('evaluation/page.html.twig', [
					'context' => 'observer',
					'page_title' => 'Evaluation #'.$evaluation->getID(),
					'prepend' => 'Spot Articulate | Evaluation #'.$evaluation->getID(),
					'evaluation' => $evaluation,
					'id' => $evaluation->getID(),
					'uuid' => $evaluation->getID(),
					'verb' => 'spot-articulate'
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
