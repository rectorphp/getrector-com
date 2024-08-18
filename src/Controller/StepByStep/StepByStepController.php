<?php

declare(strict_types=1);

namespace App\Controller\StepByStep;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

final class StepByStepController extends Controller
{
    public function __invoke(): View
    {
        return \view('step_by_step/step_by_step', [
            'page_title' => 'Step by Step',
        ]);
    }
}
