<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Application;

final class GetUserRequest
{
    public function __construct(private string $id)
    {
    }

    public function id(): string
    {
        return $this->id;
    }
}
