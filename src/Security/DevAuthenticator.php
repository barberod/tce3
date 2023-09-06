<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class DevAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly Security $security,
        private readonly RequestStack $requestStack
    ) {}

    public function supports(Request $request): ?bool
    {
        return true;
    }

    public function authenticate(Request $request): Passport
    {
        $envDevUser = $this->entityManager->getRepository(User::class)->findOneBy(['username'=>$_ENV['DEV_USER']]);

        if (!$envDevUser) {
            throw new CustomUserMessageAuthenticationException('User not found in database');
        }

        if ($envDevUser->getStatus() !== 1) {
            throw new CustomUserMessageAuthenticationException('User is blocked');
        }

        $this->logger->debug('DevAuthenticator authenticating as '.$envDevUser->getUsername());
        return new SelfValidatingPassport(
            new UserBadge(
                $_ENV['DEV_USER'],
                static fn (): UserInterface => $envDevUser
            )
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        $user = $this->security->getUser();
        if (!($this->keepsExistingUsernameInSession($user->getUsername()))) {
            $this->setUsernameInSession($user->getUsername());
            $this->generateLoggedInFlashMessage($request, $user);
        }
        $this->logger->debug('DevAuthenticator authentication success');
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];
        $this->logger->debug('DevAuthenticator authentication failure');
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    protected function generateLoggedInFlashMessage(Request $request, User $user): void {
        $alertText = sprintf("Successfully logged in as %s (%s).", $user->getDisplayName(), $user->getUsername());
        $request->getSession()->getFlashBag()->add('success', $alertText);
    }

    protected function keepsExistingUsernameInSession(string $givenUsername): bool {
        if ($givenUsername === $this->getExistingUsernameInSession()) {
            return true;
        }
        return false;
    }

    protected function getExistingUsernameInSession(): ?string {
        if ($this->requestStack->getSession()->get('existingUsername')) {
            return $this->requestStack->getSession()->get('existingUsername');
        }
        return null;
    }

    protected function setUsernameInSession(string $givenUsername): void {
        $this->requestStack->getSession()->set('existingUsername', $givenUsername);
    }
}
