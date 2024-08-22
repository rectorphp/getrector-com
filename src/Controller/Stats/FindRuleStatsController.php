<?php

declare(strict_types=1);

namespace App\Controller\Stats;

use Illuminate\Routing\Controller;
use Nette\Utils\FileSystem;
use Nette\Utils\Json;

final class FindRuleStatsController extends Controller
{
    public function __invoke()
    {
        $searchFileContents = FileSystem::read(storage_path('logs/search.json'));
        $searchFileLines = explode(PHP_EOL, $searchFileContents);
        foreach ($searchFileLines as $searchFileLine) {
            if (trim($searchFileLine) === '') {
                continue;
            }

            $searchLine = Json::decode($searchFileLine, true);
            dump($searchLine);
        }

        die;

        return view('stats.find_rule_stats');
    }
}
