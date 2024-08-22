<?php

declare(strict_types=1);

namespace App\Controller\Stats;

use App\Enum\FindRuleQuery;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Nette\Utils\FileSystem;
use Nette\Utils\Json;

final class FindRuleStatsController extends Controller
{
    public function __invoke(): View
    {
        $searchLogFilePath = storage_path('logs/search.json');
        $searchRecords = $this->loadFileToJsonItems($searchLogFilePath);

        $nodeTypes = $this->getArrayFlattenKey($searchRecords, 'nodeType');

        $sets = $this->getArrayFlattenKey($searchRecords, 'set');
        $queries = $this->getArrayFlattenKey($searchRecords, 'query');

        $nonEmptyNodeTypes = count($nodeTypes);
        $nonEmptySets = count($sets);

        // remove prepared queries
        $queries = array_diff($queries, FindRuleQuery::EXAMPLES);

        $queriesToCount = $this->groupToCount($queries);

        // remove super short queries
        $queriesToCount = array_filter($queriesToCount, function (string $query): bool {
            return strlen($query) > 2;
        }, ARRAY_FILTER_USE_KEY);

        $setsToCount = $this->groupToCount($sets);
        $nodeTypesToCount = $this->groupToCount($nodeTypes);

        return view('stats.find_rule_stats', [
            'queriesToCount' => $queriesToCount,
            'setsToCount' => $setsToCount,
            'nodeTypesToCount' => $nodeTypesToCount,
            // counts
            'nonEmptyNodeTypes' => $nonEmptyNodeTypes,
            'nonEmptySets' => $nonEmptySets,
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
     * @param mixed[] $items
     * @return mixed[]
     */
    private function groupToCount(array $items): array
    {
        $itemsToValues = array_count_values($items);
        arsort($itemsToValues);

        // at least twice
        return array_filter($itemsToValues, function ($value): bool {
            return $value > 1;
        });
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
}
