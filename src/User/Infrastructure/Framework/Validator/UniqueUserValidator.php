<?php

namespace LaSalle\GroupZero\User\Infrastructure\Framework\Validator;

use LaSalle\GroupZero\User\Application\FindUserByEmail;
use LaSalle\GroupZero\User\Application\FindUserByEmailRequest;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserValidator extends ConstraintValidator
{
    /** @var FindUserByEmail */
    private $findUserByEmail;

    public function __construct(FindUserByEmail $findUserByEmail)
    {
        $this->findUserByEmail = $findUserByEmail;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        $user = ($this->findUserByEmail)(new FindUserByEmailRequest($value));

        if (null !== $user) {
            $this
                ->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
