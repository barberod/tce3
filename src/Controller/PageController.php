<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ScratchFormType;
use Doctrine\ORM\EntityManagerInterface;
use EcPhp\CasBundle\Security\Core\User\CasUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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

		#[Route('/example-form', name: 'example-form')]
		public function exampleForm(Request $request): Response
		{
				$form = $this->createForm(ScratchFormType::class);

				$form->handleRequest($request);

				if ($form->isSubmitted() && $form->isValid()) {
						// Handle the form submission, e.g., persist data to the database
						// Redirect to a success page or perform other actions
				}

				return $this->render('evaluation/form.html.twig', [
						'context' => 'coordinator',
						'page_title' => 'Example Form',
						'prepend' => 'Example Form',
						'form' => $form,
				]);
		}

}
