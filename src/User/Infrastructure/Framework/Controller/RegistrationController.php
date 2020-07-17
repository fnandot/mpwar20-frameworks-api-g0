<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Infrastructure\Framework\Controller;

use LaSalle\GroupZero\Logging\Infrastructure\Framework\Security\SymfonyUserAuthenticator;
use LaSalle\GroupZero\User\Application\GetUserByEmail;
use LaSalle\GroupZero\User\Application\GetUserByEmailRequest;
use LaSalle\GroupZero\User\Application\RegisterUser;
use LaSalle\GroupZero\User\Application\RegisterUserRequest;
use LaSalle\GroupZero\User\Infrastructure\Framework\Form\Model\RegistrationFormModel;
use LaSalle\GroupZero\User\Infrastructure\Framework\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route({
     *     "en": "/register",
     *     "es": "/registro",
     *     "ru": "/регистр",
     *     "de": "/registrieren",
     *     "tr": "/kayıtol",
     *     "fr": "/регистр"
     * }, name="register")
     */
    public function register(
        Request $request,
        RegisterUser $registerUser,
        GetUserByEmail $getUserByEmail,
        GuardAuthenticatorHandler $guardHandler,
        SymfonyUserAuthenticator $authenticator
    ): Response {
        $model = new RegistrationFormModel();
        $form  = $this->createForm(RegistrationFormType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registerUser(
                new RegisterUserRequest(
                    $model->email(),
                    $model->plainPassword()
                )
            );

            $user = $getUserByEmail(new GetUserByEmailRequest($model->email()));

            return $guardHandler
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );
        }

        return $this->render(
            'user/register.html.twig',
            [
                'registrationForm' => $form->createView(),
            ]
        );
    }
}
