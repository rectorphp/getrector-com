<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Rector\Website\ValueObject\Routing\RouteName;
use Rector\Website\Entity\ContactMessage;
use Rector\Website\Exception\ShouldNotHappenException;
use Rector\Website\Form\ContactFormType;
use Rector\Website\Repository\ContactMessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ContactController extends AbstractController
{
    public function __construct(
        private ContactMessageRepository $contactMessageRepository,
    ) {
    }

    #[Route(path: 'contact', name: RouteName::CONTACT)]
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contactMessage = $form->getData();
            if (! $contactMessage instanceof ContactMessage) {
                throw new ShouldNotHappenException();
            }

            $this->contactMessageRepository->save($contactMessage);
            $this->addFlash('success', 'Your message is on the way!');

            return $this->redirectToRoute(RouteName::CONTACT);
        }

        return $this->render('homepage/contact.twig', [
            'contact_form' => $form->createView(),
            'page_title' => "Let's Make Impossible Happen",
        ]);
    }
}
