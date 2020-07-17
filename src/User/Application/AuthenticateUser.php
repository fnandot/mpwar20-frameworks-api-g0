<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Application;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEventBus;
use LaSalle\GroupZero\User\Application\Exception\InvalidUserPasswordException;
use LaSalle\GroupZero\User\Application\Exception\UserByEmailNotFoundException;
use LaSalle\GroupZero\User\Domain\Model\Aggregate\User;
use LaSalle\GroupZero\User\Domain\Model\Event\UserAuthenticatedDomainEvent;
use LaSalle\GroupZero\User\Domain\Model\Repository\UserRepository;
use LaSalle\GroupZero\User\Domain\Model\Service\UserPasswordValidationService;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Email;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Password;

final class AuthenticateUser
{
    /** @var UserRepository */
    private $repository;

    /** @var UserPasswordValidationService */
    private $userPasswordValidationService;

    /** @var DomainEventBus */
    private $eventBus;

    public function __construct(
        UserRepository $repository,
        UserPasswordValidationService $userPasswordValidationService,
        DomainEventBus $eventBus
    ) {
        $this->repository = $repository;
        $this->userPasswordValidationService = $userPasswordValidationService;
        $this->eventBus = $eventBus;
    }

    public function __invoke(AuthenticateUserRequest $request): void
    {
        $email = new Email($request->email());
        $password = new Password($request->password());

        $user  = $this->getUserByEmail($email);

        $this->validatePassword($user, $password);

        $this->eventBus->publish(
            new UserAuthenticatedDomainEvent(
                $user->id(),
                new DateTimeImmutable()
            )
        );
    }

    private function getUserByEmail(Email $email): User
    {
        $user = $this->repository->findOneByEmail($email);

        if (null === $user) {
            throw new UserByEmailNotFoundException($email);
        }

        return $user;
    }

    private function validatePassword(?User $user, Password $password): void
    {
        if (false === $this->userPasswordValidationService->isValid($user, $password)) {
            throw new InvalidUserPasswordException($user->id());
        }
    }
}
