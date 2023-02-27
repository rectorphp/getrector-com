<?php

namespace Rector\Website\Utils\Tests\Rector\Rector\Class_\SymfonyControllerToLaravelControllerRector\Fixture;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class SomeController extends AbstractController
{
    public function list(): Response
    {
        return $this->render('some_template.twig', [
            'key' => 'value',
        ]);
    }
}

?>
-----
<?php

namespace Rector\Website\Utils\Tests\Rector\Rector\Class_\SymfonyControllerToLaravelControllerRector\Fixture;

final class SomeController extends \Illuminate\Routing\Controller
{
    public function list(): \Illuminate\Contracts\View\View
    {
        return \view('some_template', [
            'key' => 'value',
        ]);
    }
}

?>
