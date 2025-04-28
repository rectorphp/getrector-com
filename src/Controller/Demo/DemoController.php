<?php

declare(strict_types=1);

namespace App\Controller\Demo;

use App\Entity\RectorRun;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

/**
 * @see \App\Tests\Controller\DemoControllerTest
 */
final class DemoController extends Controller
{
    public function __invoke(Request $request): View
    {
        return \view('demo/demo', [
            'page_title' => 'Try Rector Online',
            'rectorRun' => RectorRun::createEmpty(),
            'codeMirror' => true,
            'metaTitle' => 'Try Rector: Free PHP Refactoring Demo',
            'metaDescription' => 'Test Rector’s automated PHP refactoring tool for free. See instant code upgrades and improvements in action. Start your demo today!',
        ]);
    }
}
