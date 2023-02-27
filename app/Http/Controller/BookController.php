<?php

declare(strict_types=1);

namespace App\Http\Controller;

use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BookController extends \Illuminate\Routing\Controller
{
    public function __invoke(): \Illuminate\Contracts\View\View
    {
        return \view('homepage/book', [
            'page_title' => 'The Power of Automated&nbsp;Refactoring',
        ]);
    }
}
