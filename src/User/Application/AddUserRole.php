<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Application;

use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEventBus;
use LaSalle\GroupZero\User\Application\Exception\UserNotFoundException;
use LaSalle\GroupZero\User\Domain\Model\Repository\UserRepository;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserId;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserRole;

final class AddUserRole
{
    public function __construct(private UserRepository $repository, private DomainEventBus $eventBus)
    {
    }

    public function __invoke(AddUserRoleRequest $request): void
    {
        $id   = UserId::fromString($request->id());
        $role = new UserRole($request->role());

        $user = $this->repository->findOne($id);

        if (null === $user) {
            throw new UserNotFoundException($id);
        }

        $user->addRole($role);

        $this->repository->save($user);
        $this->eventBus->publish(...$user->pullDomainEvents());
    }
}
