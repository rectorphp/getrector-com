<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Rector\Website\Form\ContactFormType;
use Rector\Website\ValueObject\ContactFormData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ContactController extends AbstractController
{
    #[Route(path: 'contact', name: 'contact')]
    public function __invoke(Request $request): Response
    {
        $contactFormData = new ContactFormData();

        $form = $this->createForm(ContactFormType::class, $contactFormData);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // form was submitted
        }

        return $this->render('homepage/contact.twig', [
            'contact_form' => $form->createView(),
        ]);
    }
}
