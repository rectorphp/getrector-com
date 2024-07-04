<?php

declare(strict_types=1);

namespace App\Logging;

use Nette\Utils\Json;

final class SearchLogger
{
    /**
     * Simple search logger, to see what is needed by the community
     * and help to cover it with rules
     */
    public function log(?string $query, ?string $nodeType, ?string $set): void
    {
        $searchJson = Json::encode([
            'timestamp' => now(),
            'query' => $query,
            'nodeType' => $nodeType,
            'set' => $set,
        ]) . PHP_EOL;

        file_put_contents(__DIR__ . '/../../storage/logs/search.json', $searchJson, FILE_APPEND);
    }
}
