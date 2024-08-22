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
        $searchLogFilePath = storage_path('logs/search.json');
        $searchRecords = $this->loadFileToJsonItems($searchLogFilePath);

        $nodeTypes = $this->getArrayFlattenKey($searchRecords, 'nodeType');
        $sets = $this->getArrayFlattenKey($searchRecords, 'set');
        $queries = $this->getArrayFlattenKey($searchRecords, 'query');

        $queriesToCount = $this->groupToCount($queries);
        $setsToCount = $this->groupToCount($sets);
        $nodeTypesToCount = $this->groupToCount($nodeTypes);

        return view('stats.find_rule_stats', [
            'queriesToCount' => $queriesToCount,
            'setsToCount' => $setsToCount,
            'nodeTypesToCount' => $nodeTypesToCount,
        ]);
    }

    /**
     * @param mixed[] $items
     */
    private function getArrayFlattenKey(array $items, string $keyName): array
    {
        return array_map(function (array $item) use ($keyName) {
            return $item[$keyName];
        }, $items);
    }

    /**
     * @param mixed[] $items
     * @return mixed[]
     */
    private function groupToCount(array $items): array
    {
        $nonEmptyItems = array_filter($items);

        $itemsToValues = array_count_values($nonEmptyItems);
        arsort($itemsToValues);

        return $itemsToValues;
    }

    /**
     * @return mixed[]
     */
    private function loadFileToJsonItems(string $filePath): array
    {
        $fileContents = FileSystem::read($filePath);

        $fileLines = explode(PHP_EOL, $fileContents);

        $items = [];
        foreach ($fileLines as $searchFileLine) {
            // skip empty
            if (trim($searchFileLine) === '') {
                continue;
            }

            $items[] = Json::decode($searchFileLine, true);
        }

        return $items;
    }
}
