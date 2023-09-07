<?php

namespace App\Security;

use App\Entity\User;
use App\Service\LookupService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class DevUserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    private LoggerInterface $logger;
    private LookupService $lookup;
    private RequestStack $requestStack;

    public function __construct(
        LoggerInterface $logger,
        LookupService $lookup,
        RequestStack $requestStack
    ) {
        $this->logger = $logger;
        $this->lookup = $lookup;
        $this->requestStack = $requestStack;
    }

    /**
     * loadUserByIdentifier is an unsupported operation for this app.
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        throw new UnsupportedUserException('Unsupported operation.');
    }

    /**
     * Refreshes the user after being reloaded from the session.
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called.
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        $processedUser = $this->lookup->processUser($user->getUserIdentifier());
        if ($processedUser->getStatus() !== 1) {
            throw new UnsupportedUserException(sprintf('User "%s" is blocked.', $user->getUserIdentifier()));
        }

        if (!($this->keepsExistingUsernameInSession($processedUser->getUsername()))) {
            $this->setUsernameInSession($processedUser->getUsername());
            $this->generateLoggedInFlashMessage($processedUser);
        }

        return $user;
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass(string $class): bool
    {
        return true;
        // return User::class === $class || is_subclass_of($class, User::class);
    }

    /**
     * upgradePassword is an unsupported operation for this app.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        throw new UnsupportedUserException('Unsupported operation.');
    }

    protected function generateLoggedInFlashMessage(User $user): void {
        $alertText = sprintf("Successfully logged in as %s (%s).", $user->getDisplayName(), $user->getUsername());
        $this->requestStack->getSession()->getFlashBag()->add('success', $alertText);
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
