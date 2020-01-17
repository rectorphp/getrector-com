<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use DateTimeImmutable;
use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Nette\Utils\Strings;
use Ramsey\Uuid\Uuid;
use Rector\Website\Entity\RectorRun;
use Rector\Website\Form\RectorRunFormData;
use Rector\Website\Form\RectorRunFormType;
use Rector\Website\Process\RectorProcessRunner;
use Rector\Website\Repository\RectorRunRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Stopwatch\Stopwatch;
use Throwable;

final class DemoController extends AbstractController
{
    /**
     * @var RectorProcessRunner
     */
    private $rectorProcessRunner;

    /**
     * @var RectorRunRepository
     */
    private $rectorRunRepository;

    public function __construct(RectorProcessRunner $rectorProcessRunner, RectorRunRepository $rectorRunRepository)
    {
        $this->rectorProcessRunner = $rectorProcessRunner;
        $this->rectorRunRepository = $rectorRunRepository;
    }

    /**
     * @Route(path="demo/{id}", name="demo_detail", methods={"GET", "POST"})
     * @Route(path="demo", name="demo", methods={"GET", "POST"})
     */
    public function __invoke(Request $request): Response
    {
        $formData = new RectorRunFormData();
        $rectorRun = null;
        $rectorRunId = $request->attributes->get('id');

        $demoFileContent = FileSystem::read(__DIR__ . '/../../data/DemoFile.php');

        $formData->setContent($demoFileContent);
        $formData->setSetName('dead-code');

        if ($rectorRunId) {
            $rectorRun = $this->rectorRunRepository->get(Uuid::fromString($rectorRunId));

            $formData->setContent($rectorRun->getContent());
            $formData->setSetName($rectorRun->getSetName());
        }

        $form = $this->createForm(RectorRunFormType::class, $formData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processFormAndReturnRoute($form);
        }

        return $this->render('homepage/demo.twig', [
            'form' => $form->createView(),
            'rector_run' => $rectorRun,
        ]);
    }

    private function processFormAndReturnRoute(FormInterface $form): RedirectResponse
    {
        /** @var RectorRunFormData $formData */
        $formData = $form->getData();
        $setName = $formData->getSetName();

        $currentRectorRun = new RectorRun(
            Uuid::uuid4(),
            new DateTimeImmutable(),
            $setName,
            $formData->getContent()
        );

        /** @var RectorRun|null $previousRectorRun */
        $previousRectorRun = $this->rectorRunRepository->findMostRecentSetRun(
            $setName,
            $currentRectorRun->getContentHash()
        );

        if ($previousRectorRun) {
            return $this->redirectToDetail($previousRectorRun);
        }

        $this->rectorRunRepository->save($currentRectorRun);

        $stopwatch = new Stopwatch();
        $rectorProcessStopwatchEvent = $stopwatch->start('rector-process');

        try {
            $runResult = $this->rectorProcessRunner->run($currentRectorRun);
            $fileDiff = $runResult['file_diffs'][0]['diff'] ?? null;

            if ($fileDiff) {
                /** @var string $fileDiff */
                $fileDiff = $this->cleanFileDiff($fileDiff);
            } else {
                $fileDiff = $currentRectorRun->getContent();
            }

            $stopwatch->stop('rector-process');
            $currentRectorRun->success($fileDiff, Json::encode($runResult), $rectorProcessStopwatchEvent);
        } catch (Throwable $throwable) {
            $stopwatch->stop('rector-process');
            $currentRectorRun->fail($throwable->getMessage(), $rectorProcessStopwatchEvent);
        }

        $this->rectorRunRepository->save($currentRectorRun);

        return $this->redirectToDetail($currentRectorRun);
    }

    private function redirectToDetail(RectorRun $rectorRun): RedirectResponse
    {
        return $this->redirectToRoute('demo_detail', [
            'id' => $rectorRun->getId()->toString(),
        ]);
    }

    private function cleanFileDiff(string $fileDiff): string
    {
        // https://regex101.com/r/sI6GVY/1/
        $match = Strings::match($fileDiff, '#^.*?@@\n(?<content>.*?)$#Us');
        $fileDiff = $match['content'] ?? '';

        if (Strings::contains($fileDiff, 'No newline at end of file')) {
            $fileDiff = Strings::substring($fileDiff, 0, -strlen('\ No newline at end of file') - 1);
        }

        return $fileDiff;
    }
}
