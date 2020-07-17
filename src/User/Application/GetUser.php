<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Application;

use LaSalle\GroupZero\User\Application\Exception\UserNotFoundException;
use LaSalle\GroupZero\User\Domain\Model\Aggregate\User;
use LaSalle\GroupZero\User\Domain\Model\Repository\UserRepository;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserId;

final class GetUser
{
    /** @var UserRepository */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetUserRequest $request): User
    {
        $id = UserId::fromString($request->id());

        $user = $this->repository->findOne($id);

        if (null === $user) {
            throw new UserNotFoundException($id);
        }

        return $user;
    }
}
