<?php

declare(strict_types=1);

namespace App\RuleFilter;

use App\RuleFilter\ValueObject\RuleMetadata;
use Nette\Utils\Strings;

final class MatchingScoreResolver
{
    public function resolve(RuleMetadata $ruleMetadata, string $query): int
    {
        $score = 0;

        // resolv equery parts
        $queryParts = Strings::split($query, '#\s+#');
        $queryParts = array_map('strtolower', $queryParts);

        $rectorClassNameParts = $this->resolveRectorClassNameParts($ruleMetadata);

        foreach ($queryParts as $queryPart) {
            // make "constant" match "const", "parameter" match "param" etc., only in rule name
            if ($queryPart === 'constant') {
                $queryPart = 'const';
            }

            if ($queryPart === 'parameters') {
                $queryPart = 'param';
            }

            if (in_array($queryPart, $rectorClassNameParts, true)) {
                // name is more relevant, as description can include various words
                $score += 5;
            }
        }

        foreach ($queryParts as $queryPart) {
            if (str_contains($ruleMetadata->getDescription(), $queryPart)) {
                ++$score;
            }
        }

        return $score;
    }

    /**
     * @return string[]
     */
    private function resolveRectorClassNameParts(RuleMetadata $ruleMetadata): array
    {
        $rectorClassNameParts = Strings::split($ruleMetadata->getRuleShortClass(), '#(?=[A-Z])#');
        // lowercase all parts
        $rectorClassNameParts = array_map('strtolower', $rectorClassNameParts);

        // remove empty parts
        return array_filter($rectorClassNameParts);
    }
}
