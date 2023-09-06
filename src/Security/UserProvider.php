<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ecphp
 */

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Service\LookupService;
use EcPhp\CasBundle\Security\Core\User\CasUser;
use EcPhp\CasBundle\Security\Core\User\CasUserInterface;
use EcPhp\CasBundle\Security\Core\User\CasUserProviderInterface;
use EcPhp\CasLib\Introspection\Contract\IntrospectorInterface;
use EcPhp\CasLib\Introspection\Contract\ServiceValidate;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

use function get_class;

final class UserProvider implements CasUserProviderInterface
{
    private IntrospectorInterface $introspector;
    private LoggerInterface $logger;
    private LookupService $lookup;
    private RequestStack $requestStack;

    public function __construct(
        IntrospectorInterface $introspector,
        LoggerInterface $logger,
        LookupService $lookup,
        RequestStack $requestStack
    ) {
        $this->introspector = $introspector;
        $this->logger = $logger;
        $this->lookup = $lookup;
        $this->requestStack = $requestStack;
    }

    public function loadUserByIdentifier($identifier): UserInterface
    {
        throw new UnsupportedUserException('Unsupported operation.');
    }

    public function loadUserByResponse(ResponseInterface $response): CasUserInterface
    {
        try {
            $introspect = $this->introspector->detect($response);
        } catch (InvalidArgumentException $exception) {
            throw new AuthenticationException($exception->getMessage());
        }

        if ($introspect instanceof ServiceValidate) {
            return new CasUser($introspect->getCredentials());
        }

        throw new AuthenticationException('Unable to load user from response.');
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        throw new UnsupportedUserException(sprintf('Username "%s" does not exist.', $username));
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof CasUserInterface) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
        $this->logger->debug('****REFRESH USER**** = '.$user->getUserIdentifier());

        $processedUser = $this->lookup->processUser($user->getUserIdentifier());
        if ($processedUser->getStatus() !== 1) {
            throw new UnsupportedUserException(sprintf('User "%s" is blocked.', $user->getUserIdentifier()));
        }

        if (!($this->keepsExistingUsernameInSession($processedUser->getUsername()))) {
            $this->setUsernameInSession($processedUser->getUsername());
            $this->generateLoggedInFlashMessage($processedUser);
        }

        // return $processedUser;
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return (CasUser::class === $class || User::class );
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
