## Provide Config

By default, Rector picks `rector.php` in your project root as the configuration file.
To change that, use:

```bash
vendor/bin/rector process --config rector-custom-config.php
```

<br>

**Pro tip**: You can see current default values in the Rector config file:

* on Github: [config/config.php](https://github.com/rectorphp/rector-src/blob/main/config/config.php)
* or locally in: `/vendor/rector/rector/config/config.php`

<br>

## Spacing and Indents

Default formatting is **4 spaces** and **UNIX newlines**.
You can override:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withIndent(indentChar: ' ', indentSize: 4);
```

<br>

## Paths to Process

Define which directories or files Rector should refactor:

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/packages/Domain',
    ]);
```

You can also include all the `*.php` files from root directory:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withRootFiles();
```

<br>

## File Extensions

By default, Rector processes only `.php` files. To add more extensions, use:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withFileExtensions(['php', 'phtml']);
```

<br>

## PHP Version

Rector reads the PHP version from `composer.json`. It's very rate to use different PHP version than one provided by `composer.json`, as it might use newer syntax and break your code. If you still need different version, you can override it manually:

```php
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPhpVersion(PhpVersion::PHP_81);
```

<br>

## Using Sets

Enable built-in Rector sets:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPreparedSets(deadCode: true, codeQuality: true);
```

Check [Levels](/documentation/levels) and [Set List](/documentation/set-lists) for more.

<br>

## Composer-Based Sets

Rector can auto-enable sets based on installed packages:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withComposerBased(
        symfony: true,
        doctrine: true,
        phpunit: true,
    );
```

<br>

## Skipping Rules or Paths

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withSkip([
        __DIR__ . '/src/Legacy/*',
        __DIR__ . '/tests/Fixtures',
    ]);
```

Skip specific rule:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withSkip([
        SomeRule::class,
    ]);
```

Skip rule for specific files:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withSkip([
        SomeRule::class => [
            __DIR__ . '/src/SpecialCase.php',
        ],
    ]);
```

<br>

## Parallel Execution

Rector runs in parallel mode by default. You can customize the parallel execution like this:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withParallel(
        timeoutSeconds: 600,
        maxNumberOfProcesses: 8,
        jobSize: 20,
    );
```

Or disable:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withoutParallel();
```

<br>

## Cache & Temp Directories

By default, Rector uses `sys_get_temp_dir() . '/rector_cached_files'` path to store cache files. E.g. to verify if files were changed since last run, to run only on new or changed files. You can customize it:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withCache(__DIR__ . '/var/rector');
```



<br>

## Autoloading & Bootstrap

Rector loads project `vendor/autoload.php` by default. In case you don't use composer autoload or some of your `/vendor` dependencies are not loaded, you can include directories:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withAutoloadPaths([
        __DIR__ . '/lib',
        __DIR__ . '/vendor/legacy/package/src',
    ]);
```

You can also include exact files that contain e.g. constant definitions:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withBootstrapFiles([
        __DIR__ . '/config/constants.php',
    ]);
```

<br>

## Empower Rector to change `public` and non-final elements

By default, some Rector rules change types on `private` and `final` classes. Why? Because these classes and properties cannot be used by its children. Public and non-final elements can be used anywhere, e.g. adding a property type declaration can be risky.

Read more in [Why Final Classes make Rector and PHPStan more powerful](https://tomasvotruba.com/blog/why-final-classes-make-rector-and-phpstan-more-powerful).

If you feel confident in your codebase and want to make it even more reliable, you can empower Rector to run these rules on `public` and non-`final` elements as well:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withTreatClassesAsFinal();
```

<br>

## Nicer Fluent Call output

By default, php-parser prints new fluent calls in a single line:

```php
$some->select(...)->where(...)->getResult(...);
```

If you prefer per-line method call:

```php
$some->select(...)
    ->where(...)
    ->getResult(...);
```

You can enable it:

```php
use Rector\Config\RectorConfig;

return RectorConnfig::configure()
    ->withFluentCallNewLine();
```

<br>

## PHPStan integration

Rector load `phpstan.neon` and `phpstan.neon.dist` by default if they exist in your project root. Extensions are ignored on purpose, as some of them run project code (e.g. Doctrine) and breaks idea of static analysis. Most extensions do not bring any value to Rector, as Rector works mostly with native type declaration.

Still, there is a way to load PHPStan extension configs, in case they are needed for your specific Rector rule use case:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPHPStanConfigs([
        __DIR__ . '/config/custom-phpstan-extension.neon',
    ]);
```

<br>

## Symfony Integration

Some [Symfony Rector rules](https://getrector.com/find-rule?activeRectorSetGroup=symfony) require container metadata. Provide Symfony Container to let Rector access services (name and type mostly) and route definitions.

If your Symfony project was not run yet, dump the container first:

```bash
bin/console debug:container
```

Use in config:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    // in XML
    ->withSymfonyContainerXml(
        __DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml'
    );

    // or PHP (depending on version)
    ->withSymfonyContainerPhp(
        __DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.php'
    );
```
