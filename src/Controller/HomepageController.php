<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PostRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Nette\Utils\FileSystem;
use Nette\Utils\Json;

final class HomepageController extends Controller
{
    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    public function __invoke(): View
    {
        return \view('homepage/homepage', [
            'page_title' => "We'll Speed Up Your Development Process by 300%",
            'recentPosts' => $this->postRepository->fetchLast(5),

            'upcomingTalks' => $this->loadUpcomingTalks(),

            // seo
            'metaTitle' => 'Rector: Fast PHP Code Upgrades & Refactoring',
            'metaDescription' => 'Automate PHP code upgrades and refactoring with Rector. Save time, reduce errors, and modernize your codebase instantly. Try it now!',

            // data
            'references' => $this->loadJsonFileToArray(__DIR__ . '/../../resources/json-database/references.json'),
            'faqs' => $this->loadJsonFileToArray(__DIR__ . '/../../resources/json-database/faqs.json'),
        ]);
    }

    /**
     * @return mixed[]
     */
    private function loadUpcomingTalks(): array
    {
        $fileContents = FileSystem::read(__DIR__ . '/../../resources/json-database/upcoming_talks.json');
        $upcomingTalks = Json::decode($fileContents, forceArrays: true);

        // remove past talks
        return array_filter($upcomingTalks, static fn (array $talk): bool => $talk['date'] >= date('Y-m-d'));
    }

    /**
     * @return array<string, mixed>
     */
    private function loadJsonFileToArray(string $filePath): array
    {
        $fileContents = FileSystem::read($filePath);
        return Json::decode($fileContents, forceArrays: true);
    }
}
