<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Rector\Website\Form\ResearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ResearchController extends AbstractController
{
    /**
     * @Route(path="research", name="research", methods={"GET", "POST"})
     */
    public function __invoke(Request $request): Response
    {
        $researchForm = $this->createForm(ResearchFormType::class, null, [
            // this is needed for manual render
            'action' => $this->generateUrl('research'),
        ]);
        $researchForm->handleRequest($request);

        if ($researchForm->isSubmitted() && $researchForm->isValid()) {
            return $this->processFormAndRedirectToThankYou($researchForm);
        }

        return $this->render('research/research.twig', [
            'research_form' => $researchForm->createView(),
        ]);
    }


    private function processFormAndRedirectToThankYou(FormInterface $form): RedirectResponse
    {
        // $formData = $form->getData();

        // TODO: create and save entity

        return $this->redirectToRoute('research_thank_you');
    }
}
