<?php

declare(strict_types=1);

namespace Rector\Website\ForCompanies\Controller;

use Rector\Website\ForCompanies\Entity\ProjectCalculation;
use Rector\Website\ForCompanies\Form\ProjectCalculationFormType;
use Rector\Website\ForCompanies\FormDataFactory\ProjectCalculationFormDataFactory;
use Rector\Website\ForCompanies\ValueObject\ProjectCalculationFormData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ForCompaniesController extends AbstractController
{
    /**
     * @var ProjectCalculationFormDataFactory
     */
    private $projectCalculationFormDataFactory;

    public function __construct(ProjectCalculationFormDataFactory $projectCalculationFormDataFactory)
    {
        $this->projectCalculationFormDataFactory = $projectCalculationFormDataFactory;
    }

    /**
     * @Route(path="for-companies", name="for_companies")
     */
    public function __invoke(Request $request): Response
    {
        $projectCalculationFormData = $this->projectCalculationFormDataFactory->create();

        $projectCalculationForm = $this->createForm(ProjectCalculationFormType::class, $projectCalculationFormData);
        $projectCalculationForm->handleRequest($request);

        if ($projectCalculationForm->isSubmitted() && $projectCalculationForm->isValid()) {
            return $this->processForm($projectCalculationForm);
        }

        return $this->render('for-companies.twig', [
            'project_calculation_form_type' => $projectCalculationForm->createView(),
        ]);
    }

    private function processForm(FormInterface $form): Response
    {
        /** @var ProjectCalculationFormData $projectCalculationFormData */
        $projectCalculationFormData = $form->getData();

        $projectCalculation = new ProjectCalculation(
            $projectCalculationFormData->getInHouseMonths(),
            $projectCalculationFormData->getInHouseMonthlyCosts(),
            $projectCalculationFormData->getProjectLinesOfCode(),
        );

        return $this->render('for-companies.twig', [
            'project_calculation_form_type' => $form->createView(),
            'project_calculation' => $projectCalculation,
        ]);
    }
}
