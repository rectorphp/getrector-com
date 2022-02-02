<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Controller;

use Nette\Utils\Json;
use Nette\Utils\Strings;
use Rector\Website\Demo\Repository\RectorRunRepository;
use Rector\Website\Demo\ValueObjectFactory\RectorRunFactory;
use Rector\Website\ValueObject\Routing\RouteName;
use Rector\WebsiteDemoRunner\Entity\RectorRun;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @see \Rector\Website\Tests\Demo\Controller\DemoControllerTest
 */
final class DemoController extends AbstractController
{
    public function __construct(
        private readonly RectorRunFactory $rectorRunFactory,
        private readonly RectorRunRepository $rectorRunRepository,
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
        if ($request->isMethod(Request::METHOD_POST)) {
            $demoForm = $request->request->all('demo_form');
            $rectorRun = new RectorRun($demoForm['content'], $demoForm['config']);
        } else {
            if ($rectorRunUuid === null || ! Uuid::isValid($rectorRunUuid)) {
                $rectorRun = $this->rectorRunFactory->createEmpty();
            } else {
                $uuid = Uuid::fromString($rectorRunUuid);
                $rectorRun = $this->rectorRunRepository->get($uuid);
            }
        }

        // @todo save to json local database under uuid
        $this->rectorRunRepository->save($rectorRun);

        if ($request->isMethod('post')) {
            // process submitted form
            return $this->processFormAndReturnRoute($rectorRun);
        }

        return $this->render('demo/demo.twig', [
            'rector_run' => $rectorRun,
        ]);
    }

    private function processFormAndReturnRoute(RectorRun $rectorRun): RedirectResponse
    {
        $brefProcess = new Process([
            'vendor/bin/bref',
            'local',
            'main',
            sprintf(
                '{"content": "%s", "config": "%s"}',
                $this->inlineNewlinesToSpaces($rectorRun->getContent()),
                $this->inlineNewlinesToSpaces($rectorRun->getConfig())
            )
        ], __DIR__ . '/../../../demo-runner');

        $brefProcess->run();

        $match = Strings::match(trim($brefProcess->getOutput()), '#\{(.*?)\}$#ms');

        dump($match[0]);
        die;

        $json = Json::decode($match[0]);
        dump($json);
        die;

        // $this->demoRunner->processRectorRun($rectorRun);
        // @todo save rector run

        $demoDetailUrl = $this->urlGenerator->generate(RouteName::DEMO_DETAIL, [
            'rectorRunUuid' => $rectorRun->getId(),
        ]);

        return new RedirectResponse($demoDetailUrl);
    }

    private function inlineNewlinesToSpaces(string $content): string
    {
        return urlencode($content);
    }
}
