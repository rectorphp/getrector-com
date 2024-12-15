<?php

declare(strict_types=1);

namespace App\Utils;

final class Arrays
{
    /**
     * @param mixed[] $items
     * @return mixed[]
     */
    public static function groupToCount(array $items, int $requiredCount = 1): array
    {
        $itemsToValues = array_count_values($items);
        arsort($itemsToValues);

        // at least twice
        return array_filter($itemsToValues, fn(int $count): bool => $count > $requiredCount);
    }
}
