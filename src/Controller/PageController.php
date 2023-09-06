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
        return $this->render('page/page.html.twig', [
            'page_title' => 'Transfer Credit Evaluation',
            'page_content' => 'The quick brown fox jumps over the lazy dog.',
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

    #[Route('/secure/requester', name: 'requester')]
    public function requester(): Response
    {
        return $this->render('page/requester.html.twig', [
            'context' => 'requester',
            'page_title' => 'Transfer Credit Evaluation',
            'prepend' => 'Requester'
        ]);
    }

    #[Route('/secure/requester/evaluation', name: 'requester_evaluation')]
    public function requesterEvaluation(): Response
    {
        return $this->render('evaluation/record.html.twig', [
            'context' => 'requester',
            'page_title' => 'Evaluation #4321',
            'prepend' => 'Evaluation #4321'
        ]);
    }

    #[Route('/secure/requester/evaluation/table', name: 'requester_evaluation_table')]
    public function requesterEvaluationTable(): Response
    {
        return $this->render('evaluation/table.html.twig', [
            'context' => 'requester',
            'page_title' => 'My Evaluations',
            'prepend' => 'My Evaluations'
        ]);
    }

    #[Route('/secure/coordinator', name: 'coordinator')]
    public function coordinator(): Response
    {
        return $this->render('page/coordinator.html.twig', [
            'context' => 'coordinator',
            'page_title' => 'Transfer Credit Evaluation',
            'prepend' => 'Coordinator'
        ]);
    }

    #[Route('/secure/coordinator/evaluation', name: 'coordinator_evaluation')]
    public function coordinatorEvaluation(): Response
    {
        return $this->render('evaluation/record.html.twig', [
            'context' => 'coordinator',
            'page_title' => 'Evaluation #4321',
            'prepend' => 'Evaluation #4321'
        ]);
    }

    #[Route('/secure/coordinator/evaluation/table', name: 'coordinator_evaluation_table')]
    public function coordinatorEvaluationTable(): Response
    {
        return $this->render('evaluation/table.html.twig', [
            'context' => 'coordinator',
            'page_title' => 'Evaluations',
            'prepend' => 'Evaluations'
        ]);
    }

    #[Route('/secure/my', name: 'secure_page')]
    public function secure(LoggerInterface $logger): Response
    {
        return $this->render('page/index.html.twig', [
            'page_title' => 'Secure Page',
            'page_content' => 'This is a secure page.',
        ]);
    }

    #[Route('/secure/admin', name: 'admin_page')]
    public function admin(): Response
    {
        return $this->render('page/index.html.twig', [
            'page_title' => 'Admin Page',
            'page_content' => 'This is the admin page.',
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
