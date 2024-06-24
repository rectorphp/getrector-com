<?php

declare(strict_types=1);

namespace Rector\Website\Controller\Demo;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Rector\Website\Entity\RectorRun;

/**
 * @see \Rector\Website\Tests\Controller\DemoControllerTest
 */
final class DemoController extends Controller
{
    public function __invoke(Request $request): View
    {
        return \view('demo/demo', [
            'page_title' => 'Try Rector Online',
            'rectorRun' => RectorRun::createEmpty(),
        ]);
    }
}
