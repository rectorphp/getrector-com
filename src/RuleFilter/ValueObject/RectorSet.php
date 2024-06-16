<?php

declare(strict_types=1);

namespace Rector\Website\RuleFilter\ValueObject;

use Nette\Utils\Strings;
use Webmozart\Assert\Assert;

final class RectorSet
{
    private string $humanName;

    /**
     * @param string[] $rectorClasses
     */
    public function __construct(
        private readonly string $constantName,
        private readonly array $rectorClasses,
        private readonly string $groupName
    ) {
        if (str_starts_with($this->constantName, 'PHP_')) {
            $match = Strings::match($this->constantName, '#(?<major>\d)(?<minor>\d)#');
            Assert::isArray($match);

            $this->humanName = 'PHP ' . $match['major'] . '.' . $match['minor'];
            return;
        }

        if (str_starts_with($this->constantName, 'PHPUNIT_')) {
            $match = Strings::match($this->constantName, '#(?<major>\d+)(?<minor>\d)$#');
            if ($match) {
                $this->humanName = 'PHPUnit ' . $match['major'] . '.' . $match['minor'];
                return;
            }
        }

        if (str_starts_with($this->constantName, 'SYMFONY_')) {
            $match = Strings::match($this->constantName, '#(?<major>\d)(?<minor>\d)$#');
            if ($match) {
                $this->humanName = 'Symfony ' . $match['major'] . '.' . $match['minor'];
                return;
            }
        }

        // human name
        // uppercase to first letter of each word upper only
        $this->humanName = ucwords(strtolower(str_replace('_', ' ', $this->constantName)));
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

    public function getGroupName(): string
    {
        return $this->groupName;
    }

    public function getHumanName(): string
    {
        return $this->humanName;
    }
}
