<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Entity\Note;
use App\Form\ScratchFormType;
use App\Repository\EvaluationRepository;
use App\Service\EvaluationOptionsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('requester')]
class RequesterPageController extends AbstractController
{
		private EntityManagerInterface $entityManager;

		public function __construct(
			EntityManagerInterface $entityManager
		) {
				$this->entityManager = $entityManager;
		}

    #[Route('/requester/page', name: 'app_requester_page')]
    public function index(): Response
    {
        return $this->render('requester_page/index.html.twig', [
            'controller_name' => 'RequesterPageController',
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

		#[Route('/secure/requester/evaluation/create', name: 'requester_evaluation_create_form')]
		public function requesterEvaluationCreateForm(): Response
		{
				$note = new Note();
				$form = $this->createForm(ScratchFormType::class, $note);
				return $this->render('page/form.html.twig', [
					'context' => 'requester',
					'page_title' => 'Create a New Evaluation',
					'prepend' => 'Create a New Evaluation',
					'form' => $form,
					'verb' => 'create'
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
}
