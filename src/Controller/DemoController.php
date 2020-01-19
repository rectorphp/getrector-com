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
use function Sentry\captureException;
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
        $demoConfig = FileSystem::read(__DIR__ . '/../../data/demo-config.yaml');

        $formData->setContent($demoFileContent);
        $formData->setConfig($demoConfig);

        if ($rectorRunId) {
            $rectorRun = $this->rectorRunRepository->get(Uuid::fromString($rectorRunId));

            $formData->setContent($rectorRun->getContent());
            $formData->setConfig($rectorRun->getConfig());
        }

        $form = $this->createForm(RectorRunFormType::class, $formData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processFormAndReturnRoute($form);
        }

        return $this->render('homepage/demo.twig', [
            'demo_form' => $form->createView(),
            'rector_run' => $rectorRun,
        ]);
    }

    private function processFormAndReturnRoute(FormInterface $form): RedirectResponse
    {
        /** @var RectorRunFormData $formData */
        $formData = $form->getData();
        $config = $formData->getConfig();

        $rectorRun = $this->createRectorRun($config, $formData);

        $this->rectorRunRepository->save($rectorRun);

        $stopwatch = new Stopwatch();
        $rectorProcessStopwatchEvent = $stopwatch->start('rector-process');

        try {
            $runResult = $this->rectorProcessRunner->run($rectorRun);

            $fileDiff = $this->createFileDiff($runResult, $rectorRun);

            $rectorRun->success($fileDiff, Json::encode($runResult), $rectorProcessStopwatchEvent);
        } catch (Throwable $throwable) {
            $rectorRun->fail($throwable->getMessage(), $rectorProcessStopwatchEvent);

            // @TODO change to monolog
            // Log to sentry
            captureException($throwable);
        }

        $this->rectorRunRepository->save($rectorRun);

        return $this->redirectToDetail($rectorRun);
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

    private function redirectToDetail(RectorRun $rectorRun): RedirectResponse
    {
        return $this->redirectToRoute('demo_detail', [
            'id' => $rectorRun->getId()->toString(),
        ]);
    }

    private function createFileDiff(array $runResult, RectorRun $rectorRun): string
    {
        $fileDiff = $runResult['file_diffs'][0]['diff'] ?? null;

        if ($fileDiff) {
            /** @var string $fileDiff */
            return $this->cleanFileDiff($fileDiff);
        }

        return $rectorRun->getContent();
    }

    private function createRectorRun(string $config, RectorRunFormData $formData): RectorRun
    {
        return new RectorRun(Uuid::uuid4(), new DateTimeImmutable(), $config, $formData->getContent());
    }
}
