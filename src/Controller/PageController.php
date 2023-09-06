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
        return $this->render('page/index.html.twig', [
            'page_title' => 'Transfer Credit Evaluation',
            'page_content' => 'The quick brown fox jumps over the lazy dog.',
        ]);
    }

    #[Route('/my', name: 'secure_page')]
    public function secure(LoggerInterface $logger): Response
    {
        return $this->render('page/index.html.twig', [
            'page_title' => 'Secure Page',
            'page_content' => 'This is a secure page.',
        ]);
    }

    #[Route('/admin', name: 'admin_page')]
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
