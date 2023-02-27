<?php

declare(strict_types=1);

namespace App\Http\Controller;

use Illuminate\Routing\Controller;
use Nette\Utils\Strings;
use PackageVersions\Versions;
use Rector\Core\Application\VersionResolver;
use Rector\Website\DemoRunner;
use Rector\Website\Entity\RectorRun;
use Rector\Website\EntityFactory\RectorRunFactory;
use Rector\Website\Enum\FlashType;
use Rector\Website\Enum\RouteName;
use Rector\Website\Form\DemoFormType;
use Rector\Website\Repository\RectorRunRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

/**
 * @see \Rector\Website\Tests\Controller\DemoControllerTest
 */
final class DemoController extends Controller
{
    public function __construct(
        private readonly RectorRunRepository $rectorRunRepository,
        private readonly DemoRunner $demoRunner,
        private readonly RectorRunFactory $rectorRunFactory,
    ) {
    }

    public function __invoke(Request $request, ?string $uuid = null): Response
    {
        dump($request);
        die;

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
            'action' => route(RouteName::PROCESS_DEMO_FORM),
        ]);

        // process form submit
        $rectorReleaseDate = substr(VersionResolver::RELEASE_DATE, 0, strlen(VersionResolver::RELEASE_DATE) - 3);

        return \view('demo/demo', [
            'rector_version' => $this->resolveRectorReleaseVersion(),
            'rector_commit_hash' => Strings::after($this->resolveRectorReleaseVersion(), '@'),
            'rector_released_time' => $rectorReleaseDate,
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
}
