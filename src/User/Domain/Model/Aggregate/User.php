<?php

namespace LaSalle\GroupZero\User\Domain\Model\Aggregate;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Aggregate\Aggregate;
use LaSalle\GroupZero\User\Domain\Model\Event\UserRoleAddedDomainEvent;
use LaSalle\GroupZero\User\Domain\Model\Event\UserRoleRemovedDomainEvent;
use LaSalle\GroupZero\User\Domain\Model\Exception\UserAlreadyHasRoleException;
use LaSalle\GroupZero\User\Domain\Model\Exception\UserNotHasRoleException;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Email;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Password;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserId;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserRole;

class User extends Aggregate
{
    /** @var UserRole[] */
    protected $roles;

    public function __construct(
        protected UserId $id,
        protected Email $email,
        protected Password $password,
        UserRole ...$roles
    ) {
        $this->setRoles(...$roles);
    }

    private function setRoles(UserRole ...$roles): void
    {
        $this->roles = [];

        foreach ($roles as $role) {
            $this->addRole($role);
        }
    }

    public function addRole(UserRole $role): void
    {
        if ($this->hasRole($role)) {
            throw new UserAlreadyHasRoleException($this->id(), $role);
        }

        $this->roles[(string)$role] = $role;
        $this->recordThat(
            new UserRoleAddedDomainEvent(
                $this->id(),
                $role,
                new DateTimeImmutable()
            )
        );
    }

    public function hasRole(UserRole $role): bool
    {
        return array_key_exists((string)$role, $this->roles());
    }

    public function roles(): array
    {
        return array_values($this->roles);
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): Password
    {
        return $this->password;
    }

    public function removeRole(UserRole $role): void
    {
        if (!$this->hasRole($role)) {
            throw new UserNotHasRoleException($this->id(), $role);
        }

        unset($this->roles[(string)$role]);
        $this->recordThat(
            new UserRoleRemovedDomainEvent(
                $this->id(),
                $role,
                new DateTimeImmutable()
            )
        );
    }
}
