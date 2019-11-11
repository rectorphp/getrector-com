<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Nette\Utils\Json;
use Nette\Utils\Strings;
use Rector\Website\Entity\RectorRun;
use Rector\Website\Form\RectorRunFormType;
use Rector\Website\Process\RectorProcessRunner;
use Rector\Website\Repository\RectorRunRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
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
     * @Route(path="demo/{uuid}", name="demo_detail", methods={"GET", "POST"})
     * @Route(path="demo", name="demo", methods={"GET", "POST"})
     */
    public function __invoke(Request $request, ?RectorRun $rectorRun = null): Response
    {
        throw new AccessDeniedException();

        $form = $this->createForm(RectorRunFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processFormAndReturnRoute($rectorRun, $form);
        }

        return $this->render('homepage/demo.twig', [
            'form' => $form->createView(),
            'rector_run' => $rectorRun,
        ]);
    }

    private function processFormAndReturnRoute(?RectorRun $rectorRun, FormInterface $form): RedirectResponse
    {
        /** @var RectorRun $rectorRun */
        $rectorRun = $form->getData();

        try {
            $json = $this->rectorProcessRunner->run($rectorRun);
        } catch (Throwable $throwable) {
            throw new BadRequestHttpException('Invalid error', $throwable);
        }

        $fileDiff = $json['file_diffs'][0]['diff'] ?? null;
        if ($fileDiff) {
            /** @var string $fileDiff */
            $fileDiff = $this->cleanFileDiff($fileDiff);
        }

        $jsonStrings = Json::encode($json);
        $rectorRun->setResultJson($jsonStrings);
        $rectorRun->setContent($fileDiff);
        $this->rectorRunRepository->save($rectorRun);

        return $this->redirectToRoute('demo_detail', [
            'uuid' => $rectorRun->getUuid(),
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
