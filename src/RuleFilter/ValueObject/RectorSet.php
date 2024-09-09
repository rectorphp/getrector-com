<?php

declare(strict_types=1);

namespace App\RuleFilter\ValueObject;

use Rector\Contract\Rector\RectorInterface;
use Webmozart\Assert\Assert;

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
        // only allowed, to keep BC
        if (! in_array($name, ['phpunit/phpunit 11.0', 'doctrine/dbal 2.10'])) {
            Assert::notEmpty($rectorClasses, 'Set ' . $name . ' has no rules');
        }
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
        return $this->groupName;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
