<?php

declare(strict_types=1);

namespace App\Http\Controller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Rector\Website\DemoRunner;
use Rector\Website\Entity\RectorRun;
use Rector\Website\Enum\FlashType;
use Rector\Website\Enum\RouteName;
use Rector\Website\Repository\RectorRunRepository;
use Symfony\Component\Uid\Uuid;

final class ProcessDemoFormController extends Controller
{
    /**
     * @var int
     */
    private const INPUT_LINES_LIMIT = 100;

    public function __construct(
        private readonly DemoRunner $demoRunner,
        private readonly RectorRunRepository $rectorRunRepository,
    ) {
    }

    public function __invoke(): RedirectResponse
    {
        $demoFromData = $request->request->all()['demo_form'];
        $content = $demoFromData['content'];
        $config = $demoFromData['config'];

        $rectorRun = new RectorRun(Uuid::v4(), $content, $config);

        return $this->processFormAndReturnRoute($rectorRun);
    }

    private function processFormAndReturnRoute(RectorRun $rectorRun): RedirectResponse
    {
        if (substr_count($rectorRun->getContent(), "\n") > self::INPUT_LINES_LIMIT) {
            session()->flash(
                FlashType::ERROR,
                'Content file has too many lines. Please reduce it under 100 lines, to make it easier to read'
            );

            return to_route(RouteName::DEMO);
        }

        $this->demoRunner->processRectorRun($rectorRun);
        $this->rectorRunRepository->save($rectorRun);

        return to_route(RouteName::DEMO_DETAIL, [
            'uuid' => $rectorRun->getUuid(),
        ]);
    }

}
