<?php

declare(strict_types=1);

<<<<<<< HEAD
=======
<<<<<<<< HEAD:src/Http/Controllers/Demo/DemoController.php
>>>>>>> b18d765 (lock carbon to keep compatbility with laravel)
<<<<<<<< HEAD:src/Http/Controller/Demo/DemoController.php
namespace Rector\Website\Http\Controller\Demo;
========
namespace Rector\Website\Http\Controllers\Demo;
>>>>>>>> 3b46dec (be toelrant about uiud):src/Http/Controllers/Demo/DemoController.php
<<<<<<< HEAD
=======
========
namespace Rector\Website\Http\Controller\Demo;
>>>>>>>> b18d765 (lock carbon to keep compatbility with laravel):src/Http/Controller/Demo/DemoController.php
>>>>>>> b18d765 (lock carbon to keep compatbility with laravel)

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
