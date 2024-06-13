<?php

declare(strict_types=1);

namespace Rector\Website\RuleFilter\ValueObject;

/**
 * @api to be used somehow
 */
final class RectorSet
{
    /**
     * @param string[] $rectorClasses
     */
    public function __construct(
        private readonly string $constantName,
        private readonly array $rectorClasses
    ) {
    }

    public function hasRule(string $rectorClass): bool
    {
        return in_array($rectorClass, $this->rectorClasses, true);
    }

    public function getSlug(): string
    {
        return strtolower($this->constantName);
    }

    public function getName(): string
    {
        // @todo figure out human-readbale set name
        return str_replace('_', ' ', $this->constantName);
    }
}
