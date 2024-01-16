<?php

namespace App\Service;

use App\Entity\Evaluation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Mailer\EventListener\MessageListener;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader;
use Twig\Extra\CssInliner\CssInlinerExtension;

class EmailService
{
    private String $roText = ' This message was sent by the Registrar\'s Office at Georgia Tech.';

    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /** 
     * Send Now
     */
    public function sendNow(array $args = [])
    {
        $loader = new FilesystemLoader('../templates/emails/');
        $twig = new TwigEnvironment($loader);
        $twig->addExtension(new CssInlinerExtension());
        $messageListener = new MessageListener(null, new BodyRenderer($twig));

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($messageListener);

        $transport = Transport::fromDsn('sendmail://default', $eventDispatcher);
        $mailer = new Mailer($transport, null, $eventDispatcher);

        $recipientList = explode(",", $args["mailTo"]);

        $email = new TemplatedEmail();
        $email->from($args["fromAddress"]);
        $i = 0;
        foreach ($recipientList as $recipient) {
            $recipient = trim($recipient);
            if ($i == 0) {
                $email->to(new Address($recipient));
            } else {
                $email->addTo(new Address($recipient));
            }
            $i++;
        }
        // $email->cc('bursarappeals@finaid.gatech.edu');
        $email->subject($args["subjectLine"]);
        $email->htmlTemplate($args["htmlTemplate"]);
        $email->textTemplate($args["textTemplate"]);
        $email->context(['emailData' => $args["emailData"]]);

        if ($mailer->send($email)) {
            return true;
        } else {
            return false;
        }
    }

    public function emailToRequesterUponNewRequest(string $un, Evaluation $evaluation) {
        $recipient = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $un]);
        if ($recipient) {
            $args = array(
                'fromAddress' => 'Transfer Credit <transfercredit@registrar.gatech.edu>',
                'mailTo' => $recipient->getEmail(),
                'subjectLine' => 'New evaluation request submitted',
                'htmlTemplate' => 'html/to-requester-upon-new-request.html.twig',
                'textTemplate' => 'plaintext/to-requester-upon-new-request.txt.twig',
                'emailData' => array(
                    'evaluation' => $evaluation,
                    'recipient' => $recipient,
                    'previewText' => $recipient->getDisplayName().', your transfer credit evaluation request has been created.'.$this->roText
                )
            );
            if ($this->sendNow($args) == false) {
                // log it
            };
        }
    }

    public function emailToAssigneeUponNewAssignment(string $un, Evaluation $evaluation) {
        $recipient = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $un]);
        $requester = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $evaluation->getRequester()->getUserIdentifier()]);
        if ($recipient && $requester) {
            $args = array(
                'fromAddress' => 'Transfer Credit <transfercredit@registrar.gatech.edu>',
                'mailTo' => $recipient->getEmail(),
                'subjectLine' => '[Action Required] An evaluation was assigned to you',
                'htmlTemplate' => 'html/to-assignee-upon-assignment.html.twig',
                'textTemplate' => 'plaintext/to-assignee-upon-assignment.txt.twig',
                'emailData' => array(
                    'evaluation' => $evaluation,
                    'recipient' => $recipient,
                    'requester' => $requester,
                    'previewText' => $recipient->getDisplayName().', a transfer credit evaluation was assigned to you.'.$this->roText
                )
            );
            if ($this->sendNow($args) == false) {
                // log it
            };
        }
    }

    public function emailToRequesterUponCompletion(string $un, Evaluation $evaluation) {
        $recipient = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $un]);
        if ($recipient) {
            $args = array(
                'fromAddress' => 'Transfer Credit <transfercredit@registrar.gatech.edu>',
                'mailTo' => $recipient->getEmail(),
                'subjectLine' => 'Evaluation complete',
                'htmlTemplate' => 'html/to-requester-upon-completion.html.twig',
                'textTemplate' => 'plaintext/to-requester-upon-completion.txt.twig',
                'emailData' => array(
                    'evaluation' => $evaluation,
                    'recipient' => $recipient,
                    'previewText' => $recipient->getDisplayName().', your transfer credit evaluation request has been marked "Complete."'.$this->roText
                )
            );
            if ($this->sendNow($args) == false) {
                // log it
            };
        }
    }
}