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
 * @see \App\Livewire\RectorFilterComponent
 */
final class FindRuleStatsController extends Controller
{
    public function __invoke(): View
    {
        $searchLogFilePath = storage_path('logs/search.json');
        $searchRecords = $this->loadFileToJsonItems($searchLogFilePath);

        $nodeTypes = $this->getArrayFlattenKey($searchRecords, 'nodeType');

        $queriesToCount = $this->filterQueriesToCount($searchRecords);
        $rulesToCount = $this->filterRulesToCount($searchRecords);

        // remove values with space as legacy
        $sets = $this->getArrayFlattenKey($searchRecords, 'set');
        $sets = array_filter($sets, fn (string $set): bool => ! str_contains($set, ' '));

        $setsToCount = Arrays::groupToCount($sets, 4);

        // remove ones with "\" as legacy
        $nodeTypes = array_filter($nodeTypes, fn (string $nodeType): bool => ! str_contains($nodeType, '\\'));
        $nodeTypesToCount = Arrays::groupToCount($nodeTypes, 5);

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
            'nodeTypesToCount' => $nodeTypesToCount,
            // counts
            'nonEmptyNodeTypes' => count($nodeTypes),
            'nonEmptySets' => count($sets),
            'rulesToCount' => $rulesToCount,
            'datesToCount' => $datesToCount,
        ]);
    }

    /**
     * @param mixed[] $items
     * @return mixed[]
     */
    private function getArrayFlattenKey(array $items, string $keyName): array
    {
        $items = array_map(function (array $item) use ($keyName) {
            return $item[$keyName];
        }, $items);

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

            $items[] = Json::decode($fileLine, true);
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
        $queries = array_map('strtolower', $queries);

        $queriesToCount = Arrays::groupToCount($queries, 1);

        // remove super short queries
        return array_filter($queriesToCount, function (string $query): bool {
            // skip SQL injections
            if (str_contains($query, ' and ')) {
                return false;
            }

            if (str_contains($query, ' when ')) {
                return false;
            }

            if (str_contains($query, ' or ')) {
                return false;
            }

            if (str_contains($query, 'select ')) {
                return false;
            }

            if (str_contains($query, 'waitfor delay')) {
                return false;
            }

            if (str_contains($query, ' order by ')) {
                return false;
            }

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

        return Arrays::groupToCount($rules, 6);

    }
}
