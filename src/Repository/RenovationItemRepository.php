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
        $renovationItems = [];

        $renovationItems[] = new RenovationItem(
            'Exact PHP Version',
            'PHP version is ambiguous, defined in multiple places and with upper bracket.',
            'Single and exact PHP version. The latest available stable version to get the best performance and code quality.',
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
        );

        $renovationItems[] = new RenovationItem(
            'Static Analysis Tailored',
            'Multiple static-analysis tools, mutually covering code with the same features, taking time of CI and cost developer attention.
                <br>
                Huge baseline files with hundreds ignored errors.',
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
        );

        $renovationItems[] = new RenovationItem(
            'Autoloading Perfected',
            "Various classmap/PSR-0/files autoloading allows conflicts and make project class  loading. In some cases classes can be loaded incorrectly and cause server to crash. It's not clear, where to put new classes.",
            'Single PSR-4 loading root, simple and clear.
            <br>Fast composer loading, clear rules.<br><br>',
            <<<JSON
{
    "autoload": {
        "classmap": [
            "src",
            "libraries",
            "classes",
            "tests"
            "spec"
        ]
        "files": [
            "src/AppModule/SomeClass.php",
        ]
    }
}
JSON
            ,
            <<<JSON
{
    "autoload": {
        "psr-4": {
            "App\\\\": "src"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "App\\\\Tests\\\\": "tests"
        }
    }
}
JSON
            ,
            'json'
        );

        return $renovationItems;
    }
}
