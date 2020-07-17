<?php

namespace LaSalle\GroupZero\User\Infrastructure\Framework\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueUser extends Constraint
{
    public $message = 'Already registered user.';
}
