<?php

declare(strict_types=1);

namespace App\Repository;

use App\ValueObject\RenovationItem;

final class RenovationItemRepository
{
    /**
     * @return RenovationItem[]
     */
    public function fetchAll(): array
    {
        return [new RenovationItem(
            'PHP Version',
            'PHP version is ambiguous, defined in multiple places and with upper bracket.',
            'There is single PHP version. The latest available stable version to get the best performance and code quality.',
            <<<JSON
{
    "requires": {
        "php": "^7.2"
    },
    "config": {
        "platform": {
            "php": "7.3.4"
        }
    }
}
JSON
            ,
            <<<JSON
{
    "requires": {
        "php": "8.3"
    }
}
JSON
            ,
            'json'
        ), new RenovationItem(
            'Static Analysis',
            'Multiple static-analysis tools, mutually covering code with the same features, taking time of CI and cost developer attention.
                <br>
                Huge baseline files with hundreds ignored errors, that make purpose of analysis unreliable and dull.',
            'Simple setup with single <code>phpstan.neon</code>. No ignores. The best tool to do the job, with fast parallel run. Custom PHPStan rules to deal with your most common code review reports within your domain.',
            <<<JSON
{
    "requires-dev": {
        "phpstan/phpstan": "^1.11",
        "phpmd/phpmd": "^2.13",
        "vimeo/psalm": "^4.30"
    }
}
JSON
            ,
            <<<JSON
{
    "requires-dev": {
        "phpstan/phpstan": "^1.11"
    }
}
JSON
            ,
            'json'
        )];
    }
}
