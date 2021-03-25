<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Application;

final class RegisterUserRequest
{
    public function __construct(private string $email, private string $password)
    {
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }
}
