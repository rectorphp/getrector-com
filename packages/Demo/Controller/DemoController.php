<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Controller;

use Rector\Website\Demo\DemoRunner;
use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Demo\Form\DemoFormType;
use Rector\Website\Demo\Repository\RectorRunRepository;
use Rector\Website\Demo\ValueObjectFactory\RectorRunFactory;
use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @see \Rector\Website\Tests\Demo\Controller\DemoControllerTest
 */
final class DemoController extends AbstractController
{
    public function __construct(
        private readonly RectorRunRepository $rectorRunRepository,
        private readonly DemoRunner $demoRunner,
        private readonly RectorRunFactory $rectorRunFactory,
        private readonly FormFactoryInterface $formFactory,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    /**
     * Waits on https://github.com/symfony/symfony/pull/43854 to merge in Symfony 6.1
     */
    #[Route(path: 'demo/{rectorRunUuid}', name: RouteName::DEMO_DETAIL, methods: ['GET'])]
    #[Route(path: 'demo', name: RouteName::DEMO, methods: ['GET', 'POST'])]
    public function __invoke(Request $request, ?string $rectorRunUuid = null): Response
    {
        if ($rectorRunUuid === null || ! Uuid::isValid($rectorRunUuid)) {
            $rectorRun = $this->rectorRunFactory->createEmpty();
        } else {
            $rectorRun = $this->rectorRunRepository->get(Uuid::fromString($rectorRunUuid));
        }

        $demoForm = $this->formFactory->create(DemoFormType::class, $rectorRun, [
            // this is needed for manual render
            'action' => $this->urlGenerator->generate(RouteName::DEMO),
        ]);

        // process form submit
        if ($request->isMethod(Request::METHOD_POST)) {
            $demoFromData = $request->request->all()['demo_form'];
            $content = $demoFromData['content'];
            $config = $demoFromData['config'];

            $rectorRun = new RectorRun(Uuid::v4(), $content, $config);

            return $this->processFormAndReturnRoute($rectorRun);
        }

        return $this->render('demo/demo.twig', [
            'demo_form' => $demoForm->createView(),
            'rector_run' => $rectorRun,
        ]);
    }

    private function processFormAndReturnRoute(RectorRun $rectorRun): RedirectResponse
    {
        $this->demoRunner->processRectorRun($rectorRun);
        $this->rectorRunRepository->save($rectorRun);

        $demoDetailUrl = $this->urlGenerator->generate(RouteName::DEMO_DETAIL, [
            'rectorRunUuid' => $rectorRun->getUuid(),
        ]);

        return new RedirectResponse($demoDetailUrl);
    }
}
