<?php

declare(strict_types=1);

namespace App\Controller\Stats;

use App\Enum\FindRuleQuery;
use App\Utils\Arrays;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Nette\Utils\FileSystem;
use Nette\Utils\Json;

/**
 * @see \App\Livewire\FindRuleComponent
 */
final class FindRuleStatsController extends Controller
{
    public function __invoke(): View
    {
        $searchLogFilePath = storage_path('logs/search.json');
        $searchRecords = $this->loadFileToJsonItems($searchLogFilePath);

        $queriesToCount = $this->filterQueriesToCount($searchRecords);
        $rulesToCount = $this->filterRulesToCount($searchRecords);

        $sets = $this->getArrayFlattenKey($searchRecords, 'set');

        $setsToCount = Arrays::groupToCount($sets, 4);
        $setsToCount = array_slice($setsToCount, 0, 10);

        // day by day stats
        $timestamps = $this->getArrayFlattenKey($searchRecords, 'timestamp');
        $dates = [];

        foreach ($timestamps as $timestamp) {
            $dates[] = Carbon::make($timestamp)->format('Y-m-d');
        }

        $datesToCount = Arrays::groupToCount($dates);
        ksort($datesToCount);

        return view('stats.find_rule_stats', [
            'queriesToCount' => $queriesToCount,
            'setsToCount' => $setsToCount,
            // counts
            'rulesToCount' => $rulesToCount,
            'datesToCount' => $datesToCount,
        ]);
    }

    /**
     * @param mixed[][] $items
     * @return mixed[]
     */
    private function getArrayFlattenKey(array $items, string $keyName): array
    {
        $items = array_map(fn (array $item) => $item[$keyName], $items);

        // remove empty ones
        return array_filter($items);
    }

    /**
     * @return mixed[]
     */
    private function loadFileToJsonItems(string $filePath): array
    {
        $fileContents = FileSystem::read($filePath);

        $fileLines = explode(PHP_EOL, $fileContents);

        $items = [];
        foreach ($fileLines as $fileLine) {
            // skip empty
            if (trim($fileLine) === '') {
                continue;
            }

            $items[] = Json::decode($fileLine, forceArrays: true);
        }

        return $items;
    }

    /**
     * @param mixed[] $searchRecords
     * @return array<string, int>
     */
    private function filterQueriesToCount(array $searchRecords): array
    {
        $queries = $this->getArrayFlattenKey($searchRecords, 'query');

        // remove prepared queries
        $queries = array_diff($queries, FindRuleQuery::EXAMPLES);

        // lowercase to standardize similar searches
        $queries = array_map(strtolower(...), $queries);

        $queriesToCount = Arrays::groupToCount($queries, 1);

        // remove super short queries
        return array_filter($queriesToCount, function (string $query): bool {
            // skip rector rules
            if (str_ends_with($query, 'rector')) {
                return false;
            }

            return strlen($query) > 6;
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param mixed[] $searchRecords
     * @return array<string, int>
     */
    private function filterRulesToCount(array $searchRecords): array
    {
        $rules = $this->getArrayFlattenKey($searchRecords, 'query');

        // keep only items with "Rector" suffix
        $rules = array_filter($rules, fn (string $rule): bool => str_ends_with($rule, 'Rector'));

        $rulesToCount = Arrays::groupToCount($rules, 6);

        return array_slice($rulesToCount, 0, 10);

    }
}
