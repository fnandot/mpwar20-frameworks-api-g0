<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Application;

final class GetUserByEmailRequest
{
    public function __construct(private string $email)
    {
    }

    public function email(): string
    {
        return $this->email;
    }
}
