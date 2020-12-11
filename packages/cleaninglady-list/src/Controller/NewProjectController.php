<?php

declare(strict_types=1);

namespace Rector\Website\CleaningLadyList\Controller;

use Rector\Website\CleaningLadyList\Entity\Project;
use Rector\Website\CleaningLadyList\Entity\ProjectCheckbox;
use Rector\Website\CleaningLadyList\Form\ProjectFormType;
use Rector\Website\CleaningLadyList\Repository\CheckboxRepository;
use Rector\Website\CleaningLadyList\Repository\ProjectCheckboxRepository;
use Rector\Website\CleaningLadyList\Repository\ProjectRepository;
use Rector\Website\ValueObject\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class NewProjectController extends AbstractController
{
    public function __construct(
        private CheckboxRepository $checkboxRepository,
        private ProjectRepository $projectRepository,
        private ProjectCheckboxRepository $projectCheckboxRepository
    ) {
    }

    #[Route(path: 'cleaning-lady-list/new', name: RouteName::NEW_PROJECT)]
    public function __invoke(Request $request): Response
    {
        $project = new Project();
        $projectForm = $this->createForm(ProjectFormType::class, $project);
        $projectForm->handleRequest($request);

        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            return $this->processFormRequest($project);
        }

        return $this->render('project/new_project.twig', [
            'project_form' => $projectForm->createView(),
            'page_title' => 'New List',
        ]);
    }

    private function processFormRequest(Project $project): RedirectResponse
    {
        $checkboxes = $this->checkboxRepository->findByFramework($project->getTargetFramework());

        foreach ($checkboxes as $checkbox) {
            $projectCheckbox = new ProjectCheckbox();
            $projectCheckbox->setProject($project);
            $projectCheckbox->addCheckbox($checkbox);

            $this->projectCheckboxRepository->save($projectCheckbox);
        }

        $this->projectRepository->save($project);

        return $this->redirectToRoute(RouteName::PROJECT_DETAIL, [
            'id' => $project->getId(),
        ]);
    }
}
