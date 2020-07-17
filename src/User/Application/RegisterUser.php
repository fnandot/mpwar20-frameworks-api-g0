<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Application;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEventBus;
use LaSalle\GroupZero\User\Application\Exception\AlreadyExistingUserException;
use LaSalle\GroupZero\User\Domain\Model\Aggregate\User;
use LaSalle\GroupZero\User\Domain\Model\Event\UserRegisteredDomainEvent;
use LaSalle\GroupZero\User\Domain\Model\Factory\UserFactory;
use LaSalle\GroupZero\User\Domain\Model\Repository\UserRepository;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Email;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Password;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserRole;

final class RegisterUser
{
    /** @var UserRepository */
    private $repository;

    /** @var UserFactory */
    private $factory;

    /** @var DomainEventBus */
    private $eventBus;

    public function __construct(UserRepository $repository, UserFactory $factory, DomainEventBus $eventBus)
    {
        $this->repository = $repository;
        $this->factory    = $factory;
        $this->eventBus   = $eventBus;
    }

    public function __invoke(RegisterUserRequest $request): void
    {
        $email = new Email($request->email());

        if (null !== $this->repository->findOneByEmail($email)) {
            throw new AlreadyExistingUserException($email);
        }

        $user = $this
            ->factory
            ->register(
                $email,
                new Password($request->password()),
                UserRole::user()
            );

        $this->repository->save($user);
        $this->eventBus->publish(
            new UserRegisteredDomainEvent(
                $user->id(),
                $user->email(),
                $user->roles(),
                new DateTimeImmutable()
            )
        );
    }
}
