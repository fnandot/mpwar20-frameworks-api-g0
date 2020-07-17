<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Infrastructure\Framework\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('logging_web_summary_list');
        }

        $error        = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(UrlGeneratorInterface $urlGenerator): RedirectResponse
    {
        $redirectResponse = new RedirectResponse($urlGenerator->generate('user_web_login'));

        $redirectResponse->headers->clearCookie('mercureAuthorization', '/.well-known/mercure');

        return $redirectResponse;
    }
}
