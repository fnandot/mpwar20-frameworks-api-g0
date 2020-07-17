<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Application;

final class RegisterUserRequest
{
    /** @var string */
    private $email;

    /** @var string */
    private $password;

    public function __construct(string $email, string $password)
    {
        $this->email    = $email;
        $this->password = $password;
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
