<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('page/homepage.html.twig', [
            'page_title' => 'Transfer Credit Evaluation'
        ]);
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

    #[Route('/secure/coordinator/evaluation', name: 'coordinator_evaluation_table')]
    public function coordinatorEvaluationTable(): Response
    {
        return $this->render('evaluation/table.html.twig', [
            'context' => 'coordinator',
            'page_title' => 'Evaluations',
            'prepend' => 'Evaluations'
        ]);
    }

    #[Route('/secure/coordinator/evaluation/{uuid}', name: 'coordinator_evaluation_page')]
    public function coordinatorEvaluationPage(string $uuid): Response
    {
        return $this->render('evaluation/page.html.twig', [
            'context' => 'coordinator',
            'page_title' => 'Evaluation #'.$uuid,
            'prepend' => 'Evaluation #'.$uuid,
            'uuid' => $uuid
        ]);
    }

    #[Route('/secure/coordinator/evaluation/{uuid}/{verb}', name: 'coordinator_evaluation_form')]
    public function coordinatorEvaluationForm(string $uuid, string $verb): Response
    {
        return $this->render('evaluation/page.html.twig', [
            'context' => 'coordinator',
            'page_title' => 'Evaluation #'.$uuid,
            'prepend' => 'Evaluation #'.$uuid,
            'uuid' => $uuid,
            'verb' => $verb
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
