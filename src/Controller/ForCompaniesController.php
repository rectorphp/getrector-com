<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Rector\Website\Form\ContactFormType;
use Rector\Website\FormProcessor\ContactFormProcessor;
use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ForCompaniesController extends AbstractController
{
    public function __construct(
        private ContactFormProcessor $contactFormProcessor
    )
    {
    }

    #[Route(path: 'for-companies', name: RouteName::FOR_COMPANIES)]
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->contactFormProcessor->process($form, RouteName::FOR_COMPANIES);
        }

        return $this->render('homepage/for_companies.twig', [
            'contact_form' => $form->createView(),
            'title' => 'Do you have a Successful Project, but old Code Programmers Hate',
        ]);
    }
}
