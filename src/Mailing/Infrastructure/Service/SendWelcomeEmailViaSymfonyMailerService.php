<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Mailing\Infrastructure\Service;

use LaSalle\GroupZero\Mailing\Domain\Model\Service\SendWelcomeEmailService;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

final class SendWelcomeEmailViaSymfonyMailerService implements SendWelcomeEmailService
{
    /** @var MailerInterface */
    private $mailer;

    /** @var TranslatorInterface */
    private $translator;

    /** @var string */
    private $senderEmail;

    /** @var string */
    private $senderName;

    public function __construct(
        MailerInterface $mailer,
        TranslatorInterface $translator,
        string $senderEmail,
        string $senderName
    ) {
        $this->mailer      = $mailer;
        $this->translator  = $translator;
        $this->senderEmail = $senderEmail;
        $this->senderName  = $senderName;
    }

    public function __invoke(Email $email)
    {
        $message = (new TemplatedEmail())
            ->subject($this->translator->trans('email.welcome.subject'))
            ->from(new Address($this->senderEmail, $this->senderName))
            ->to($email->toString())
            ->htmlTemplate('mailing/email/welcome.html.twig')
            ->context([]);

        $this->mailer->send($message);
    }
}
