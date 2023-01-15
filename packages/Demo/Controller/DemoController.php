<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Controller;

use Nette\Utils\Strings;
use PackageVersions\Versions;
use Rector\Core\Application\VersionResolver;
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
    /**
     * @var int
     */
    private const INPUT_LINES_LIMIT = 100;

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
    #[Route(path: 'demo/{uuid}', name: RouteName::DEMO_DETAIL, methods: ['GET'])]
    #[Route(path: 'demo', name: RouteName::DEMO, methods: ['GET', 'POST'])]
    public function __invoke(Request $request, ?string $uuid = null): Response
    {
        if ($uuid === null || ! Uuid::isValid($uuid)) {
            $rectorRun = $this->rectorRunFactory->createEmpty();
        } else {
            $rectorRun = $this->rectorRunRepository->get(Uuid::fromString($uuid));
            if (! $rectorRun instanceof RectorRun) {
                // item not found
                $errorMessage = sprintf('Rector run "%s" was not found. Try to run code again for new result', $uuid);
                $this->addFlash('danger', $errorMessage);

                return $this->redirectToRoute(RouteName::DEMO);
            }
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
            'rector_version' => $this->resolveRectorReleaseVersion(),
            'rector_commit_hash' => Strings::after($this->resolveRectorReleaseVersion(), '@'),
            'rector_released_time' => substr(
                VersionResolver::RELEASE_DATE,
                0,
                strlen(VersionResolver::RELEASE_DATE) - 3
            ),
            'demo_form' => $demoForm->createView(),
            'rector_run' => $rectorRun,
        ]);
    }

    private function resolveRectorReleaseVersion(): string
    {
        $rectorVersion = Versions::getVersion('rector/rector');
        $extractAt = explode('@', $rectorVersion);

        return $extractAt[0] . '@' . substr($extractAt[1], 0, 6);
    }

    private function processFormAndReturnRoute(RectorRun $rectorRun): RedirectResponse
    {
        if (substr_count($rectorRun->getContent(), "\n") > self::INPUT_LINES_LIMIT) {
            $this->addFlash(
                'danger',
                'Content file has too many lines. Please reduce it under 100 lines, to make it easier to read'
            );
            return $this->redirectToRoute(RouteName::DEMO);
        }

        $this->demoRunner->processRectorRun($rectorRun);
        $this->rectorRunRepository->save($rectorRun);

        return $this->redirectToRoute(RouteName::DEMO_DETAIL, [
            'uuid' => $rectorRun->getUuid(),
        ]);
    }
}
