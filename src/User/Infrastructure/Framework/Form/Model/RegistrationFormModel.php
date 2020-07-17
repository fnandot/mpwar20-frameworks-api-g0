<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Infrastructure\Framework\Form\Model;

use LaSalle\GroupZero\User\Infrastructure\Framework\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints\Regex;

final class RegistrationFormModel
{
    /**
     * @UniqueUser(
     *     message="error.user.not_unique"
     * )
     *
     * @var string|null
     */
    private $email;

    /**
     * @Regex(
     *     pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,4096}$/",
     *     match=true,
     *     message="error.password.invalid"
     * )
     *
     * @var string|null
     */
    private $plainPassword;

    public function email(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function plainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
}
