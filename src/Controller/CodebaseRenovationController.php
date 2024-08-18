<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\RenovationItemRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

final class CodebaseRenovationController extends Controller
{
    public function __construct(
        private readonly RenovationItemRepository $renovationItemRepository,
    ) {
    }

    public function __invoke(): View
    {
        return \view('homepage/codebase_renovation', [
            'page_title' => 'Codebase Renovation',
            'renovationItems' => $this->renovationItemRepository->fetchAll(),
        ]);
    }
}
