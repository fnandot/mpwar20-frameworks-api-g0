<?php

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Security;

use LaSalle\GroupZero\User\Application\AuthenticateUser;
use LaSalle\GroupZero\User\Application\AuthenticateUserRequest;
use LaSalle\GroupZero\User\Application\Exception\InvalidUserPasswordException;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserRole;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class SymfonyUserAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private CsrfTokenManagerInterface $csrfTokenManager,
        private AuthenticateUser $authenticateUser
    ) {
    }

    public function supports(Request $request)
    {
        return 'user_web_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        // Load / create our user however you need.
        // You can do this by calling the user provider, or with custom logic here.
        $user = $userProvider->loadUserByUsername($credentials['username']);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Username could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        try {
            ($this->authenticateUser)(new AuthenticateUserRequest($user->getUsername(), $credentials['password']));

            return true;
        } catch (InvalidUserPasswordException) {
            return false;
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $response = parent::onAuthenticationFailure($request, $exception);

        $response->headers->clearCookie('mercureAuthorization', '/.well-known/mercure');

        return $response;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            $response = new RedirectResponse($targetPath);
        } else {
            $response = new RedirectResponse($this->urlGenerator->generate('logging_web_summary_list'));
        }

        $user = $token->getUser();

        $token = (new Builder())
            ->withClaim(
                'mercure',
                [
                    'subscribe' => array_map(
                        static function (UserRole $role) {
                            return sprintf('%s/roles/%s', 'http://localhost:8080/api', $role);
                        },
                        $user->roles()
                    ),
                ]
            )
            ->getToken(
                new Sha256(),
                new Key('zoR7T4VXw6El6R5UfXeCSBt00OqzReaB')
            );

        $response->headers->set(
            'set-cookie',
            sprintf('mercureAuthorization=%s; path=/.well-known/mercure; httponly; SameSite=strict', $token)
        );

        return $response;
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('user_web_login');
    }
}
