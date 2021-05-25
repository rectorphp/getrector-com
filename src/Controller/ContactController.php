<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;
use Rector\Website\Form\ContactFormType;
use Rector\Website\FormProcessor\ContactFormProcessor;

use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ContactController extends AbstractController
{
    public function __construct(
        private ContactFormProcessor $contactFormProcessor,
        private Recaptcha3Validator $recaptcha3Validator,
    ) {
    }

    #[Route(path: 'contact', name: RouteName::CONTACT)]
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $lastResponse = $this->recaptcha3Validator->getLastResponse();
            if ($lastResponse instanceof \ReCaptcha\Response && ! $lastResponse->isSuccess()) {
                // probably a bot
                $this->addFlash('error', 'Google now sees you as a bot, so your message was not sent.');
                return $this->redirectToRoute(RouteName::CONTACT);
            }

            return $this->contactFormProcessor->process($form, RouteName::CONTACT);
        }

        return $this->render('homepage/contact.twig', [
            'contact_form' => $form->createView(),
            'page_title' => "Let's Make Impossible Happen",
        ]);
    }
}
