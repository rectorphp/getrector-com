<?php

declare(strict_types=1);

namespace Rector\Website\CleaningLadyList\Controller;

use Rector\Website\CleaningLadyList\Entity\Project;
use Rector\Website\CleaningLadyList\Repository\CheckboxRepository;
use Rector\Website\ValueObject\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ListDetailController extends AbstractController
{
    public function __construct(private CheckboxRepository $checkboxRepository)
    {
    }

    #[Route('list/{id}', name: RouteName::LIST_DETAIL)]
    public function __invoke(Project $project): Response
    {
        $currentFramework = (string) $project->getCurrentFramework();

        $checkboxes = $this->checkboxRepository->findByFramework($currentFramework);
        return $this->render('project/show.twig', [
            'project' => $project,
            'checkboxes' => $checkboxes,
        ]);
    }
}
