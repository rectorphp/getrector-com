<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Nette\Utils\Strings;
use Rector\Website\Process\RectorProcessRunner;
use Rector\Website\ValueObject\RectorRunValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Rector\Website\Form\RectorRunFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

final class DemoController extends AbstractController
{
    /**
     * @Route(path="demo", name="demo", methods={"GET", "POST"})
     */
    public function __invoke(Request $request, RectorProcessRunner $rectorProcessRunner): Response
    {
        $form = $this->createForm(RectorRunFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var RectorRunValueObject $rectorRun */
            $rectorRun = $form->getData();

            try {
                $json = $rectorProcessRunner->run($rectorRun);
            } catch (Throwable $throwable) {
                throw new BadRequestHttpException('Invalid error', $throwable);
            }

            $fileDiff = $json['file_diffs'][0]['diff'] ?? null;

            if ($fileDiff) {
                /** @var string $fileDiff */
                $fileDiff = $this->cleanFileDiff($fileDiff);
            }

            return $this->render('homepage/demo.twig', [
                'form' => $form->createView(),
                'file_diff' => $fileDiff,
            ]);
        }

        return $this->render('homepage/demo.twig', [
            'form' => $form->createView(),
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
