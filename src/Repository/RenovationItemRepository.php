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

        $renovationItems[] = new RenovationItem(
            'Coding Standard Working for You',
            'Your project has overwhelming mix of IDE setup, code sniffer/php-cs-fixer XML/stringy-PHP, pre-commit hook and PSR-2 coding standard (deprecated since 2019).<br>
            IDE and tools make you change code manually, <strong>put your developers under stress and distracts them from delivering business features</strong>.',
            'Single tool that fixes coding standard in blazing fast parallel CLI run.<br><br>
            Run ECS with prepared sets beyond PSR-12. Including neatly indented arrays, clean and meaningful docblocks, removed unused imports, and standardized spacing of every element.',
            <<<'JSON'
<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->append([
        __DIR__ . '/rector.php',
        __DIR__ . '/composer-dependency-analyser.php'
    ]);

return PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'linebreak_after_opening_tag' => true,
        'mb_str_functions' => true,
        'no_php4_constructor' => true,
        'blank_line_between_import_groups' => false,
    ])
    ->setFinder($finder);
JSON
            ,
            <<<JSON
<?php

use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([__DIR__ . '/src'])
    ->withRootFiles()
    ->withPreparedSets(
        psr12: true, common: true, symplify: true
    );
JSON
            ,
            'php'
        );

        $renovationItems[] = new RenovationItem(
            'Automated Instant CI Feedback',
            "Knowledge of your project is scattered in multiple places, from README, through Confluence, to senior developers' memory. To achieve a change, you have to verify multiple places and ask multiple people about best practices. Verification of a single change takes hour of manual testing and is prone to human error.",
            'The source of truth is in the CI. Every change is verified through exactly defined up-to-date steps. If any knowledge needs update or is obsolete, it will be change in CI setup.
            <br>
            That way any developer, junior, senior or contractor, has access to very same knowledge.',
            <<<'JSON'
// 1. verify in README

// 2. check internal wiki
      (mostly outdated and leading to wrong path)

// 3. run tests on server manually
      (re-run for new commit)

// 4. don't forget to ping responsible devs on Slack
JSON
            ,
            <<<JSON
name: Code Analysis

on:
  pull_request: null

jobs:
  code_analysis:
    strategy:
      matrix:
        actions:
          - run: vendor/bin/class-leak check src

          - run: php bin/verify-aws-secrets.hp
JSON
            ,
            'yaml'
        );

        return $renovationItems;
    }
}
