<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EcPhp\CasBundle\Security\Core\User\CasUser;
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
		#[IsGranted('manager')]
    public function manager(): Response
    {
        return $this->render('page/homepage.html.twig', [
            'context' => 'manager',
            'page_title' => 'Transfer Credit Evaluation',
            'prepend' => 'Manager'
        ]);
    }

    #[Route('/secure/observer', name: 'observer_home')]
		#[IsGranted('observer', null)]
    public function observer(): Response
    {
        return $this->render('page/homepage.html.twig', [
            'context' => 'observer',
            'page_title' => 'Transfer Credit Evaluation',
            'prepend' => 'Observer'
        ]);
    }

    #[Route('/secure/observer/evaluation', name: 'observer_evaluation_table')]
		#[IsGranted('observer', null)]
    public function observerEvaluationTable(): Response
    {
        return $this->render('evaluation/table.html.twig', [
            'context' => 'observer',
            'page_title' => 'Evaluations',
            'prepend' => 'Evaluations'
        ]);
    }

    #[Route('/secure/observer/evaluation/{uuid}', name: 'observer_evaluation_page')]
		#[IsGranted('observer', null)]
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
		#[IsGranted('observer', null)]
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
		#[IsGranted('observer', null)]
    public function observerCourseTable(): Response
    {
        return $this->render('course/table.html.twig', [
            'context' => 'observer',
            'page_title' => 'Courses',
            'prepend' => 'Courses'
        ]);
    }

    #[Route('/secure/observer/department', name: 'observer_department_table')]
		#[IsGranted('observer', null)]
    public function observerDepartmentTable(): Response
    {
        return $this->render('department/table.html.twig', [
            'context' => 'observer',
            'page_title' => 'Departments',
            'prepend' => 'Departments'
        ]);
    }

    #[Route('/secure/observer/institution', name: 'observer_institution_table')]
		#[IsGranted('observer', null)]
    public function observerInstitutionTable(): Response
    {
        return $this->render('institution/table.html.twig', [
            'context' => 'observer',
            'page_title' => 'Institutions',
            'prepend' => 'Institutions'
        ]);
    }



    #[Route('/secure/assignee', name: 'assignee_home')]
		#[IsGranted('assignee', null)]
    public function assignee(): Response
    {
        return $this->render('page/homepage.html.twig', [
            'context' => 'assignee',
            'page_title' => 'Transfer Credit Evaluation',
            'prepend' => 'Assignee'
        ]);
    }

    #[Route('/secure/assignee/evaluation', name: 'assignee_evaluation_table')]
		#[IsGranted('assignee', null)]
    public function assigneeEvaluationTable(): Response
    {
        return $this->render('evaluation/table.html.twig', [
            'context' => 'assignee',
            'page_title' => 'Evaluations',
            'prepend' => 'Evaluations'
        ]);
    }

    #[Route('/secure/assignee/evaluation/{uuid}', name: 'assignee_evaluation_page')]
		#[IsGranted('assignee', null)]
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
		#[IsGranted('assignee', null)]
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
		#[IsGranted('assignee', null)]
    public function assigneeCourseTable(): Response
    {
        return $this->render('course/table.html.twig', [
            'context' => 'assignee',
            'page_title' => 'Courses',
            'prepend' => 'Courses'
        ]);
    }

    #[Route('/secure/assignee/department', name: 'assignee_department_table')]
		#[IsGranted('assignee', null)]
    public function assigneeDepartmentTable(): Response
    {
        return $this->render('department/table.html.twig', [
            'context' => 'assignee',
            'page_title' => 'Departments',
            'prepend' => 'Departments'
        ]);
    }

    #[Route('/secure/assignee/institution', name: 'assignee_institution_table')]
		#[IsGranted('assignee', null)]
    public function assigneeInstitutionTable(): Response
    {
        return $this->render('institution/table.html.twig', [
            'context' => 'assignee',
            'page_title' => 'Institutions',
            'prepend' => 'Institutions'
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
