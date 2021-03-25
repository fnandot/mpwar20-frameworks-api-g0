<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Application;

final class FindUserByEmailRequest
{
    public function __construct(private string $email)
    {
    }

    public function email(): string
    {
        return $this->email;
    }
}
