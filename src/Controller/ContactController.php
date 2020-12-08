<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

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
    /**
     * @var string
     */
    private const ROUTE_CONTACT = 'contact';

    public function __construct(private ContactMessageRepository $contactMessageRepository, )
    {
    }

    #[Route(path: 'contact', name: self::ROUTE_CONTACT)]
    public function __invoke(Request $request): Response
    {
        //, $contactFormData);
        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contactMessage = $form->getData();
            if (! $contactMessage instanceof ContactMessage) {
                throw new ShouldNotHappenException();
            }

            // @todo add created at with doctrine behaviors
            dump($contactMessage);
            die;

            // @todo handle
            $this->contactMessageRepository->save($contactMessage);

            $this->addFlash('success', 'Your message is on the way!');
            // form was submitted

            // rediredt to self
            return $this->redirectToRoute(self::ROUTE_CONTACT);
        }

        return $this->render('homepage/contact.twig', [
            'contact_form' => $form->createView(),
        ]);
    }
}
