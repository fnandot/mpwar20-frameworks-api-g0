<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Application;

final class FindUserByEmailRequest
{
    /** @var string */
    private $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function email(): string
    {
        return $this->email;
    }
}