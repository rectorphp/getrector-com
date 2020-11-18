---
id: 4
title: "Rector is Moving From YAML to PHP Configs - What Changes and How to Get Ready?"
perex: |
    In July 2020, we started to move from the configuration in YAML to one defined in PHP.
    The YAML configuration **is now deleted in Rector core** and won't be supported next 0.8 release.
    <br>
    <br>
    What benefits PHP brings, how the rule configuration changes, and **how to prepare yourself**?
---

<br>

You might have noticed the warning on Rector run:

<img src="/assets/images/blog/2020/moving_yaml_to_php_warning.png" class="img-thumbnail mt-3 mb-3">

This happens when you're using `rector.yaml` config in your project.

If you're already on `rector.php`, this message is gone.

## Testing YAML to PHP in the Wild

It took around 3 weeks to switch configs, test out practical impacts, and resolve bugs related to method call values merging.

What is *method call values merging*? Rector uses multiple Symfony configs to keep sets separated, e.g., symfony40, symfony41, symfony42, etc. Each of them renames some classes, so they call `configure()` method with their values.

```php
<?php

use Rector\Renaming\Rector\Name\RenameClassRector;

// first config
$services = $containerConfigurator->services();
$services->set(RenameClassRector::class)
    ->call('configure', [[
        RenameClassRector::OLD_TO_NEW_CLASSES => [
            'old_2' => 'new_2',
        ],
    ],
]);
```

<br>

If you have 2 configs with same method call and same argument, **only the latest one is used**:

```php
// another config
$services->set(RenameClassRector::class)
    ->call('configure', [[
        RenameClassRector::OLD_TO_NEW_CLASSES => [
            'old_1' => 'new_1',
        ],
    ],
]);
```

**We fixed this behavior from "override" to "merge"** with [custom file loader](https://github.com/rectorphp/rector/pull/4081/files#diff-1f79bb7ffdca1f08c0a6ac35bbb2d928). It collects all the arguments and sets them once in the end before the container is compiled.

<br>

Now, even our [demo](/demo) runs on PHP configs:

<img src="/assets/images/blog/2020/moving_yaml_to_php_demo.png" class="img-thumbnail mt-3 mb-3">

Saying that yesterday [we dropped YAML config support from Rector core](https://github.com/rectorphp/rector/pull/4081).

<br>

## How to Switch from `rector.yaml` to `rector.php`

What if you have a lot of custom setup in `rector.yaml`? Is there a list of changes that you need to do manually?

Don't worry. We're all lazy here. [Migrify toolkit](https://tomasvotruba.com/blog/2020/07/27/how-to-switch-from-yaml-xml-configs-to-php-today-with-migrify/) got you covered:

```bash
composer require migrify/config-transformer --dev
```

Then provide files/directories with YAML files you want to switch to PHP:

```bash
vendor/bin/config-transformer switch-format rector.yaml
```

## What are Instant Benefits of `rector.php`?

There are 2 groups of benefits: one is related to general format switch - **read about them in [10 Cool Features You Get after switching from YAML to PHP Configs](https://tomasvotruba.com/blog/2020/07/16/10-cool-features-you-get-after-switching-from-yaml-to-php-configs/)**.

The other is related to the smarter configuration of Rector rules.

Before, the configuration was done in an array-random-like manner.

```yaml
services:
    # rename class
    Rector\Renaming\Rector\Name\RenameClassRector:
        $oldToNewClasses:
            'OldClass': 'NewClass'
```

Be sure to [bother your brain with memorizing](https://tomasvotruba.com/blog/2018/08/27/why-and-how-to-avoid-the-memory-lock/) `$oldToNewClasses`. And this is just `key: value` complexity - the simplest one.

What about **more common nested configuration**, e.g., constant rename?

```yaml
services:
    Rector\Renaming\Rector\ClassConstFetch\RenameClassConstantRector:
        $oldToNewConstantsByClass:
            'SomeClass::OLD_CONSTANT': 'NEW_CONSTANT'
            # or...?
            'SomeClass':
                'OLD_CONSTANT': 'NEW_CONSTANT'
            # or...?
            'SomeClass':
                'OLD_CONSTANT': ['NEW_CONSTANT']
            # or...?
            ['SomeClass', 'OLD_CONSTANT']: 'NEW_CONSTANT'
```

This is a perfect example of "have an n-guesses, then rage quit" developer experience—freedom at its worst.

## Value Objects Configuration FTW

There is a simple solution with PHP that could never be used in YAML - **value objects**.

```php
final class ClassConstantRename
{
    // ...
    public function __construct(string $oldClass, string $oldConstant, string $newConstant)
    {
        // ...
    }
}
```

### What Value is in Value Objects?

By only making single object, we got **instantly high code quality for free**:

- type validation, string/int/constants
- IDE autocompletes names of parameters, so we know what arguments are needed
- a single line of configuration, no nesting to nesting to nesting to nesting

<br>

How do value objects look like in practice?

```php

use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstantRector;
use function Rector\SymfonyPhpConfig\inline_value_objects;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(RenameClassConstantRector::class)
        ->call('configure', [[
            RenameClassConstantRector::CLASS_CONSTANT_RENAME => inline_value_objects([
                new ClassConstantRename('Cake\View\View', 'NAME_ELEMENT', 'TYPE_ELEMENT')
            ])
        ]]);
};
```

That's it. Use IDE autocomplete and value objects.

**There is no need to look into the rule and look for the parameter name**, the meaning of that particular key or value, if the key/value is required, optional, named, or implicit.

<br>

We're thinking of [introducing rule <=> value object naming convention](https://github.com/rectorphp/rector/issues/4086), so it gets even simpler:

- `RenameClassConstantRector` for rule
- `RenameClassConstant` for value object

Thanks to PHP, now all these optimizations are possible, and PHPStorm powers help us. And this is [just a start](https://twitter.com/VotrubaT/status/1297974889148813322).

<br>

Happy coding!
