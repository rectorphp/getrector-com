<?php

declare(strict_types=1);

namespace Rector\Website\Mailing;

use Rector\Website\Entity\ContactMessage;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

final class MailSender
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    public function sendContactMessageTo(ContactMessage $contactMessage, string $toEmailAddress): void
    {
        $fromEmailAddress = new Address($contactMessage->getEmail(), $contactMessage->getName());

        $email = new Email();
        $email->from('contact-form@getrector.org')
            ->to($toEmailAddress)
            ->subject('Rector contact form')
            ->replyTo($fromEmailAddress)
            ->text($contactMessage->getMessage());

        $this->mailer->send($email);
    }
}
