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

    public function hasRule(string $rectorClass): bool
    {
        return in_array($rectorClass, $this->rectorClasses, true);
    }

    public function getSlug(): string
    {
        return strtolower($this->groupName) . '_' . strtolower($this->name);
    }

    public function getGroupName(): string
    {
        if ($this->groupName === SetGroup::PHPUNIT) {
            // special case for upper case :)
            return 'PHPUnit';
        }

        return ucfirst($this->groupName);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
