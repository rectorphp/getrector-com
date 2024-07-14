<?php

declare(strict_types=1);

namespace App\RuleFilter\ValueObject;

use Rector\Contract\Rector\RectorInterface;
use Rector\Set\Enum\SetGroup;

final readonly class RectorSet
{
    /**
     * @param array<class-string<RectorInterface>> $rectorClasses
     */
    public function __construct(
        private string $groupName,
        private string $name,
        private array $rectorClasses,
    ) {
    }

    /**
     * @param class-string $rectorClass
     */
    public function hasRule(string $rectorClass): bool
    {
        return in_array($rectorClass, $this->rectorClasses, true);
    }

    public function getRuleCount(): int
    {
        return count($this->rectorClasses);
    }

    public function getSlug(): string
    {
        $uniqueName = $this->groupName . ' ' . $this->name;
        return str($uniqueName)->slug('-')
            ->toString();
    }

    public function getGroupName(): string
    {
        if (in_array($this->groupName, [SetGroup::PHPUNIT, SetGroup::PHP], true)) {
            // special case for upper case :)
            return strtoupper($this->groupName);
        }

        return ucfirst($this->groupName);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
