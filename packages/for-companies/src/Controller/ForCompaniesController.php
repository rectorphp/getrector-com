<?php

declare(strict_types=1);

namespace Rector\Website\ForCompanies\Controller;

use Rector\Website\ForCompanies\Form\ProjectCalculationFormType;
use Rector\Website\Demo\ValueObject\DemoFormData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class ForCompaniesController extends AbstractController
{
    /**
     * @Route(path="for-companies", name="for_companies")
     */
    public function __invoke(Request $request)
    {
        $projectCalculationForm = $this->createForm(ProjectCalculationFormType::class);

        $projectCalculationForm->handleRequest($request);

        if ($projectCalculationForm->isSubmitted() && $projectCalculationForm->isValid()) {
            $this->processForm($projectCalculationForm);
        }

        return $this->render('for-companies.twig', [
            'project_calculation_form_type' => $projectCalculationForm->createView()
        ]);
    }

    private function processForm(\Symfony\Component\Form\FormInterface $form): void
    {
        $formData = $form->getData();
        dump($formData);
        die;
    }
}
