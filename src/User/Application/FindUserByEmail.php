<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Application;

use LaSalle\GroupZero\User\Domain\Model\Aggregate\User;
use LaSalle\GroupZero\User\Domain\Model\Repository\UserRepository;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Email;

final class FindUserByEmail
{
    public function __construct(private UserRepository $repository)
    {
    }

    public function __invoke(FindUserByEmailRequest $request): ?User
    {
        return $this->repository->findOneByEmail(new Email($request->email()));
    }
}
