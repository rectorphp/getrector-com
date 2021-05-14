<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Controller;

use Rector\Website\Demo\DemoRunner;
use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Demo\Form\DemoFormType;
use Rector\Website\Demo\Repository\RectorRunRepository;
use Rector\Website\Demo\ValueObjectFactory\RectorRunFactory;
use Rector\Website\Exception\ShouldNotHappenException;
use Rector\Website\Twig\ResponseRenderer;
use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @see \Rector\Website\Tests\Demo\Controller\DemoControllerTest
 */
final class DemoController
{
    public function __construct(
        private RectorRunRepository $rectorRunRepository,
        private DemoRunner $demoRunner,
        private RectorRunFactory $rectorRunFactory,
        private ResponseRenderer $responseRenderer,
        private FormFactoryInterface $formFactory,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    #[Route(path: 'demo/{rectorRun}', name: RouteName::DEMO_DETAIL, methods: ['GET'])]
    #[Route(path: 'demo', name: RouteName::DEMO, methods: ['GET', 'POST'])]
    public function __invoke(Request $request, ?RectorRun $rectorRun = null): Response
    {
        if ($rectorRun === null) {
            $rectorRun = $this->rectorRunFactory->createEmpty();
        }

        $demoForm = $this->formFactory->create(DemoFormType::class, $rectorRun, [
            // this is needed for manual render
            'action' => $this->urlGenerator->generate(RouteName::DEMO),
        ]);

        $demoForm->handleRequest($request);
        if ($demoForm->isSubmitted() && $demoForm->isValid()) {
            return $this->processFormAndReturnRoute($demoForm);
        }

        return $this->responseRenderer->render('demo/demo.twig', [
            'demo_form' => $demoForm->createView(),
            'rector_run' => $rectorRun,
        ]);
    }

    private function processFormAndReturnRoute(FormInterface $form): RedirectResponse
    {
        $rectorRun = $form->getData();
        if (! $rectorRun instanceof RectorRun) {
            throw new ShouldNotHappenException();
        }

        $this->demoRunner->processRectorRun($rectorRun);
        $this->rectorRunRepository->save($rectorRun);

        $demoDetailUrl = $this->urlGenerator->generate(RouteName::DEMO_DETAIL, [
            'rectorRun' => $rectorRun->getId(),
        ]);

        return new RedirectResponse($demoDetailUrl);
    }
}
