<?php

declare(strict_types=1);

<<<<<<< HEAD
=======
<<<<<<<< HEAD:src/Http/Controllers/Demo/DemoDetailController.php
>>>>>>> b18d765 (lock carbon to keep compatbility with laravel)
<<<<<<<< HEAD:src/Http/Controller/Demo/DemoDetailController.php
namespace Rector\Website\Http\Controller\Demo;
========
namespace Rector\Website\Http\Controllers\Demo;
>>>>>>>> 3b46dec (be toelrant about uiud):src/Http/Controllers/Demo/DemoDetailController.php
<<<<<<< HEAD
=======
========
namespace Rector\Website\Http\Controller\Demo;
>>>>>>>> b18d765 (lock carbon to keep compatbility with laravel):src/Http/Controller/Demo/DemoDetailController.php
>>>>>>> b18d765 (lock carbon to keep compatbility with laravel)

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Rector\Website\Entity\RectorRun;
use Rector\Website\Repository\RectorRunRepository;

final class DemoDetailController extends Controller
{
    public function __construct(
        private readonly RectorRunRepository $rectorRunRepository,
    ) {
    }

    public function __invoke(string $uuid): View|RedirectResponse
    {
        $rectorRun = $this->rectorRunRepository->get($uuid);
        if (! $rectorRun instanceof RectorRun) {
            return redirect_with_error(
                DemoController::class,
                sprintf('Rector run "%s" was not found. Try to run code again for new result', $uuid)
            );
        }

        return \view('demo/demo', [
            'page_title' => 'Try Rector Online',
            'rector_run' => $rectorRun,
        ]);
    }
}
