<?php

declare(strict_types=1);

namespace App\Logging;

use Nette\Utils\Json;

final class RectorFuleSearchLogger
{
    /**
     * Skip typical SQL injection attacks, as no value
     * @var string[]
     */
    private const EXCLUDED_QUERIES = ['order by', 'select', ' and ', ' or ', ' limit ', 'when', 'waitfor delay'];

    /**
     * Simple search logger, to see what is needed by the community
     * and help to cover it with rules
     */
    public function log(?string $query, ?string $nodeType, ?string $set): void
    {
        if ($query === null && $nodeType === null && $set === null) {
            return;
        }

        // skip typical SQL injections attacks
        if ($this->isSQLInjection($query)) {
            return;
        }

        $searchJson = Json::encode([
            'timestamp' => now(),
            'query' => $query,
            'nodeType' => $nodeType,
            'set' => $set,
        ]) . PHP_EOL;

        file_put_contents(__DIR__ . '/../../storage/logs/search.json', $searchJson, FILE_APPEND);
    }

    private function isSQLInjection(?string $query): bool
    {
        if (! is_string($query)) {
            return false;
        }

        $lowerQuery = strtolower($query);

        foreach (self::EXCLUDED_QUERIES as $excludedQuery) {
            if (str_contains($lowerQuery, $excludedQuery)) {
                return true;
            }
        }

        return false;
    }
}
