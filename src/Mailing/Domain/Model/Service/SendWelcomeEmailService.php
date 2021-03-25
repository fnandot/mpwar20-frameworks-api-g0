<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\Mailing\Domain\Model\Service;

use LaSalle\GroupZero\User\Domain\Model\ValueObject\Email;

interface SendWelcomeEmailService
{
    public function __invoke(Email $email);
}
