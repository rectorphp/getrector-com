<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

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
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
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
        // $formData = new RectorRunFormData();

        $form = $this->createForm(RectorRunFormType::class/*, $formData*/);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processFormAndReturnRoute($form);
        }

        $rectorRun = null;
        $id = $request->query->get('id');
        if ($id) {
            // TODO: search RectorRun by $request->query->get('id') or throw 404
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

        $currentRectorRun = new RectorRun(
            Uuid::uuid4(),
            $formData->getSetName(),
            $formData->getContent()
        );

        /** @var RectorRun|null $previousRectorRun */
        $previousRectorRun = null; // @TODO: Search by content hash and rector set

        if ($previousRectorRun) {
            return $this->redirectToRectorRun($previousRectorRun);
        }

        $this->rectorRunRepository->save($currentRectorRun);

        try {
            $runResult = $this->rectorProcessRunner->run($currentRectorRun);
        } catch (Throwable $throwable) {
            throw new BadRequestHttpException('Invalid error', $throwable);
        }

        $fileDiff = $runResult['file_diffs'][0]['diff'] ?? null;
        if ($fileDiff) {
            /** @var string $fileDiff */
            $fileDiff = $this->cleanFileDiff($fileDiff);
        }

        $currentRectorRun->updateResult($fileDiff, Json::encode($runResult));

        $this->rectorRunRepository->save($currentRectorRun);

        return $this->redirectToRectorRun($currentRectorRun);
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


    private function redirectToRectorRun(RectorRun $rectorRun): RedirectResponse
    {
        return $this->redirectToRoute('demo_detail', [
            'id' => $rectorRun->getId()->toString(),
        ]);
    }
}
