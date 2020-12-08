<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Controller;

use Rector\Website\Demo\DemoRunner;
use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Demo\Form\DemoFormType;
use Rector\Website\Demo\Repository\RectorRunRepository;
use Rector\Website\Demo\ValueObjectFactory\RectorRunFactory;
use Rector\Website\Exception\ShouldNotHappenException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DemoController extends AbstractController
{
    /**
     * @var string
     */
    private const ROUTE_DEMO_DETAIL = 'demo_detail';

    /**
     * @var string
     */
    private const ROUTE_DEMO = 'demo';

    public function __construct(
        private RectorRunRepository $rectorRunRepository,
        private DemoRunner $demoRunner,
        private RectorRunFactory $rectorRunFactory
    ) {
    }

    #[Route('demo/{rectorRun}', name: self::ROUTE_DEMO_DETAIL, methods: ['GET'])]
    #[Route(self::ROUTE_DEMO, name: self::ROUTE_DEMO, methods: ['GET', 'POST'])]
    public function __invoke(Request $request, ?RectorRun $rectorRun = null): Response
    {
        if ($rectorRun === null) {
            $rectorRun = $this->rectorRunFactory->createEmpty();
        }

        $demoForm = $this->createForm(DemoFormType::class, $rectorRun, [
            // this is needed for manual render
            'action' => $this->generateUrl(self::ROUTE_DEMO),
        ]);

        $demoForm->handleRequest($request);
        if ($demoForm->isSubmitted() && $demoForm->isValid()) {
            return $this->processFormAndReturnRoute($demoForm);
        }

        return $this->render('demo/demo.twig', [
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

        return $this->redirectToRoute(self::ROUTE_DEMO_DETAIL, [
            'rectorRun' => $rectorRun->getId(),
            '_fragment' => 'demo_result',
        ]);
    }
}
