<?php

declare(strict_types=1);

namespace Rector\Website\Controller\Demo;

use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Rector\Website\Entity\RectorRun;

final class CustomRuleController extends Controller
{
    public function __invoke(): View
    {
        return \view('demo/custom-rule', [
            'page_title' => 'Design Custom Rule',
            'rector_run' => RectorRun::createEmpty(),
        ]);
    }
}
