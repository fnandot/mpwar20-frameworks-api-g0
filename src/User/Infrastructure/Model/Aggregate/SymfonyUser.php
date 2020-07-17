<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Infrastructure\Model\Aggregate;

use LaSalle\GroupZero\User\Domain\Model\Aggregate\User;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Password;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserRole;
use Symfony\Component\Security\Core\User\UserInterface;

class SymfonyUser extends User implements UserInterface
{
    /**
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email();
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return array_unique(
            array_map(
                static function (UserRole $role): string {
                    return 'ROLE_'.strtoupper((string) $role);
                },
                $this->roles()
            )
        );
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password();
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }

    public function setPassword(Password $password): void
    {
        $this->password = $password;
    }
}
