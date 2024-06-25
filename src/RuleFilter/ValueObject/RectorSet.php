<?php

declare(strict_types=1);

namespace App\RuleFilter\ValueObject;

use Rector\Contract\Rector\RectorInterface;

final readonly  class RectorSet
{
    /**
     * @param string $groupName
     * @param string $name
     * @param array<class-string<RectorInterface>> $rectorClasses
     */
    public function __construct(
        private string $groupName,
        private string $name,
        private array $rectorClasses,
    ) {
    }

    public function hasRule(string $rectorClass): bool
    {
        return in_array($rectorClass, $this->rectorClasses, true);
    }

    public function getSlug(): string
    {
        return strtolower($this->groupName) .  '_' . strtolower($this->name);
    }

    public function getGroupName(): string
    {
        return $this->groupName;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
