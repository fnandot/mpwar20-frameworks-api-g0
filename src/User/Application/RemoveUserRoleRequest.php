<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Application;

final class RemoveUserRoleRequest
{
    public function __construct(private string $id, private string $role)
    {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function role(): string
    {
        return $this->role;
    }
}
