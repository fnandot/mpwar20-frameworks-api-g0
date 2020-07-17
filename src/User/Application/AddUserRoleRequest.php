<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Application;

final class AddUserRoleRequest
{
    /** @var string */
    private $id;

    /** @var string */
    private $role;

    public function __construct(string $id, string $role)
    {
        $this->id   = $id;
        $this->role = $role;
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
