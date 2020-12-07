<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Controller;

use Rector\Website\Demo\DataProvider\DemoLinkProvider;
use Rector\Website\Demo\DemoRunner;
use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Demo\Form\DemoFormType;
use Rector\Website\Demo\Repository\RectorRunRepository;
use Rector\Website\Demo\ValueObject\DemoFormData;
use Rector\Website\Demo\ValueObjectFactory\DemoFormDataFactory;
use Rector\Website\Demo\ValueObjectFactory\RectorRunFactory;
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
        private DemoFormDataFactory $demoFormDataFactory,
        private DemoRunner $demoRunner,
        private DemoLinkProvider $demoLinkProvider,
        private RectorRunFactory $rectorRunFactory
    ) {
    }

    #[Route('demo/{rectorRun}', name: self::ROUTE_DEMO_DETAIL, methods: ['GET'])]
    #[Route(self::ROUTE_DEMO, name: self::ROUTE_DEMO, methods: ['GET', 'POST'])]
    public function __invoke(Request $request, ?RectorRun $rectorRun = null): Response
    {
        $form = $this->demoFormDataFactory->createFromRectorRun($rectorRun);
        $demoForm = $this->createForm(DemoFormType::class, $form, [
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
            'demo_links' => $this->demoLinkProvider->provide(),
        ]);
    }

    private function processFormAndReturnRoute(FormInterface $form): RedirectResponse
    {
        /** @var DemoFormData $demoFormData */
        $demoFormData = $form->getData();
        $config = $demoFormData->getConfig();

        $rectorRun = $this->rectorRunFactory->create($config, $demoFormData);
        $this->demoRunner->runAndPopulateRunResult($rectorRun);

        $this->rectorRunRepository->save($rectorRun);

        return $this->redirectToRoute(self::ROUTE_DEMO_DETAIL, [
            'rectorRun' => $rectorRun->getId(),
            '_fragment' => 'result',
        ]);
    }
}
