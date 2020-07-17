<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Infrastructure\Model\Service;

use LaSalle\GroupZero\User\Domain\Model\Aggregate\User;
use LaSalle\GroupZero\User\Domain\Model\Service\UserPasswordValidationService;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Password;
use LaSalle\GroupZero\User\Infrastructure\Model\Aggregate\SymfonyUser;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class SymfonyUserPasswordValidationService implements UserPasswordValidationService
{
    /** @var UserPasswordEncoderInterface */
    private $userPasswordEncoderInterface;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoderInterface)
    {
        $this->userPasswordEncoderInterface = $userPasswordEncoderInterface;
    }

    /**
     * @param SymfonyUser $user
     */
    public function isValid(User $user, Password $password): bool
    {
        return $this->userPasswordEncoderInterface->isPasswordValid($user, (string) $password);
    }
}
