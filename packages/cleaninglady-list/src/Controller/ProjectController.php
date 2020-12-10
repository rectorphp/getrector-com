<?php

declare(strict_types=1);

namespace Rector\Website\CleaningLadyList\Controller;

use Rector\Website\CleaningLadyList\Entity\Project;
use Rector\Website\CleaningLadyList\Entity\ProjectCheckbox;
use Rector\Website\CleaningLadyList\Form\ProjectFormType;
use Rector\Website\CleaningLadyList\Repository\CheckboxRepository;
use Rector\Website\CleaningLadyList\Repository\ProjectCheckboxRepository;
use Rector\Website\CleaningLadyList\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProjectController extends AbstractController
{
    public function __construct(
        private CheckboxRepository $checkboxRepository,
        private ProjectRepository $projectRepository,
        private ProjectCheckboxRepository $projectCheckboxRepository
    ) {
    }

    #[Route(path: 'cleaning-lady-list', name: 'lists')]
    public function create(Request $request): Response
    {
        $project = new Project();
        $projectForm = $this->createForm(ProjectFormType::class, $project);
        $projectForm->handleRequest($request);
        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            return $this->processFormRequest($project);
        }

        return $this->render('project/create.twig', [
            'project_form' => $projectForm->createView(),
        ]);
    }

    #[Route('project/checkbox/check', name: 'project.checkbox.check')]
    public function checkProjectCheckbox(Request $request): JsonResponse
    {
        $getContent = $request->getContent();
        $content = json_decode($getContent . '', false, 512, JSON_THROW_ON_ERROR);
        $submittedToken = $content->token;
        $projectCheckboxId = $content->projectCheckboxId;
        if ($this->isCsrfTokenValid('check-blank-token', $submittedToken)) {
            $projectCheckbox = $this->projectCheckboxRepository->get($projectCheckboxId);
            $projectCheckbox->inverseCompleteAt();

            $this->projectCheckboxRepository->save($projectCheckbox);

            return $this->json([
                'success' => true,
                'result' => $projectCheckbox->getCompleteAtAsString(),
            ]);
        }
        return $this->json([
            'success' => false,
        ]);
    }

    private function processFormRequest(Project $project): RedirectResponse
    {
        $desiredFramework = $project->getDesiredFramework();
        $checkboxes = $this->checkboxRepository->findByFramework($desiredFramework);

        foreach ($checkboxes as $checkbox) {
            $projectCheckbox = new ProjectCheckbox();
            $projectCheckbox->setProject($project);
            $projectCheckbox->addCheckbox($checkbox);

            $this->projectCheckboxRepository->persist($projectCheckbox);
        }

        $project->setTimezone('Prague');
        $this->projectRepository->save($project);

        return $this->redirectToRoute('project.show', [
            'id' => $project->getId(),
        ]);
    }
}
