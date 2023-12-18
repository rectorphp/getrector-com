<?php

declare(strict_types=1);

namespace Rector\Website\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Rector\Website\EntityFactory\RectorRunFactory;

/**
 * @see \Rector\Website\Tests\Controller\DemoControllerTest
 */
final class DemoController extends Controller
{
    public function __construct(
        private readonly RectorRunFactory $rectorRunFactory,
    ) {
    }

    public function __invoke(): View
    {
        return \view('demo/demo', [
            'page_title' => 'Try Rector Online',
            'rector_run' => $this->rectorRunFactory->createEmpty(),
        ]);
    }
}
