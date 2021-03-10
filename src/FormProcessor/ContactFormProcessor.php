<?php

declare(strict_types=1);

namespace Rector\Website\FormProcessor;

use Rector\Website\Entity\ContactMessage;
use Rector\Website\Exception\ShouldNotHappenException;
use Rector\Website\Mailing\MailSender;
use Rector\Website\Repository\ContactMessageRepository;
use Rector\Website\ValueObject\MailContact;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ContactFormProcessor
{
    public function __construct(
        private ContactMessageRepository $contactMessageRepository,
        private MailSender $mailSender,
        private SessionInterface $session,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function process(FormInterface $form, string $redirectRoute): RedirectResponse
    {
        $contactMessage = $form->getData();
        if (! $contactMessage instanceof ContactMessage) {
            throw new ShouldNotHappenException();
        }

        $this->contactMessageRepository->save($contactMessage);
        $this->mailSender->sendContactMessageTo($contactMessage, [MailContact::MAIN, MailContact::MARKETING]);

        $flashBag = $this->session->getFlashBag();
        $flashBag->add('success', 'Your message is on the way!');

        $url = $this->urlGenerator->generate($redirectRoute);
        return new RedirectResponse($url);
    }
}
