<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use DateTimeImmutable;
use Nette\Utils\FileSystem;
use Ramsey\Uuid\Uuid;
use Rector\Website\DemoRunner;
use Rector\Website\Entity\RectorRun;
use Rector\Website\Form\DemoFormType;
use Rector\Website\Repository\RectorRunRepository;
use Rector\Website\ValueObject\DemoFormData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DemoController extends AbstractController
{
    /**
     * @var string[][]
     */
    private $demoLinks = [];

    /**
     * @var RectorRunRepository
     */
    private $rectorRunRepository;

    /**
     * @var DemoRunner
     */
    private $demoRunner;

    /**
     * @param string[][] $demoLinks
     */
    public function __construct(RectorRunRepository $rectorRunRepository, DemoRunner $demoRunner, array $demoLinks)
    {
        $this->rectorRunRepository = $rectorRunRepository;
        $this->demoRunner = $demoRunner;
        $this->demoLinks = $demoLinks;
    }

    /**
     * @Route(path="demo/{rectorRun}", name="demo_detail", methods={"GET"})
     * @Route(path="demo", name="demo", methods={"GET", "POST"})
     */
    public function __invoke(Request $request, ?RectorRun $rectorRun = null): Response
    {
        $formData = $this->createDemoFormData($rectorRun);

        $demoForm = $this->createForm(DemoFormType::class, $formData, [
            // this is needed for manual render
            'action' => $this->generateUrl('demo'),
        ]);
        $demoForm->handleRequest($request);

        if ($demoForm->isSubmitted() && $demoForm->isValid()) {
            return $this->processFormAndReturnRoute($demoForm);
        }

        return $this->render('homepage/demo.twig', [
            'demo_form' => $demoForm->createView(),
            'rector_run' => $rectorRun,
            'demo_links' => $this->demoLinks,
        ]);
    }

    private function createDemoFormData(?RectorRun $rectorRun): DemoFormData
    {
        if ($rectorRun) {
            return new DemoFormData($rectorRun->getContent(), $rectorRun->getConfig());
        }

        // default values
        $demoContent = FileSystem::read(__DIR__ . '/../../data/DemoFile.php');
        $demoConfig = FileSystem::read(__DIR__ . '/../../data/demo-config.yaml');

        return new DemoFormData($demoContent, $demoConfig);
    }

    private function processFormAndReturnRoute(FormInterface $form): RedirectResponse
    {
        /** @var DemoFormData $demoFormData */
        $demoFormData = $form->getData();
        $config = $demoFormData->getConfig();

        $rectorRun = $this->createRectorRun($config, $demoFormData);
        $this->demoRunner->runAndPopulateRunResult($rectorRun);

        $this->rectorRunRepository->save($rectorRun);

        return $this->redirectToRoute('demo_detail', [
            'rectorRun' => $rectorRun->getId(),
        ]);
    }

    private function createRectorRun(string $config, DemoFormData $demoFormData): RectorRun
    {
        return new RectorRun(Uuid::uuid4(), new DateTimeImmutable(), $config, $demoFormData->getContent());
    }
}
