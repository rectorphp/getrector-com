<?php

declare(strict_types=1);

namespace App\Controller\Demo;

use App\Entity\CustomRuleRun;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

final class CustomRuleController extends Controller
{
    public function __invoke(): View
    {
        return \view('demo/custom-rule', [
            'page_title' => 'Design Custom Rule',
            'rectorRun' => CustomRuleRun::createEmpty(),
        ]);
    }
}
