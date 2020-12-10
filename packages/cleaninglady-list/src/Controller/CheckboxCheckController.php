<?php

declare(strict_types=1);

namespace Rector\Website\CleaningLadyList\Controller;

use Nette\Utils\Json;
use Rector\Website\CleaningLadyList\Repository\CheckboxRepository;
use Rector\Website\CleaningLadyList\Repository\ProjectCheckboxRepository;
use Rector\Website\CleaningLadyList\Repository\ProjectRepository;
use Rector\Website\ValueObject\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class CheckboxCheckController extends AbstractController
{
    public function __construct(
        private CheckboxRepository $checkboxRepository,
        private ProjectRepository $projectRepository,
        private ProjectCheckboxRepository $projectCheckboxRepository
    ) {
    }

    #[Route('project/checkbox/check', name: RouteName::CHECKBOX_CHECK)]
    public function __invoke(Request $request): JsonResponse
    {
        $content = Json::decode((string) $request->getContent());

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
}
