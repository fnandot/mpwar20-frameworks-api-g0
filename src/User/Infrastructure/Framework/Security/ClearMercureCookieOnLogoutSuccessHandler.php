<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Infrastructure\Framework\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class ClearMercureCookieOnLogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function onLogoutSuccess(Request $request)
    {
        $redirectResponse = new RedirectResponse($this->urlGenerator->generate('user_web_login'));

        $redirectResponse->headers->clearCookie('mercureAuthorization', '/.well-known/mercure');

        return $redirectResponse;
    }
}
