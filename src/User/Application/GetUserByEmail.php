<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Application;

use LaSalle\GroupZero\User\Application\Exception\UserByEmailNotFoundException;
use LaSalle\GroupZero\User\Domain\Model\Aggregate\User;
use LaSalle\GroupZero\User\Domain\Model\Repository\UserRepository;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Email;

final class GetUserByEmail
{
    public function __construct(private UserRepository $repository)
    {
    }

    public function __invoke(GetUserByEmailRequest $request): User
    {
        $email = new Email($request->email());

        $user = $this->repository->findOneByEmail($email);

        if (null === $user) {
            throw new UserByEmailNotFoundException($email);
        }

        return $user;
    }
}
