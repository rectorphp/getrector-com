---
id: 34
title: "New in Rector 0.12 - Introducing Rector Config with Autocomplete"
perex: |
    Rector is using Symfony container configuration to build the service model. While it brings automated autowiring, array autowiring, and native container features, the syntax to configure Rector has been complex and talkative.
    <br><br>
    The hard question is: how can we refactor from Symfony, have a custom Rector config class but keep using its features?
---

<img src="/assets/images/blog/2022/rector_config.gif" style="max-width: 35em" class="img-thumbnail mb-4">

A year ago we switched to [a distribution repository releases](/blog/prefixed-rector-by-default). It now allows us to add own `RectorConfig` class on top of Symfony internals.

**The `RectorConfig` helps you by**:

* full IDE autocomplete,
* isolation from Symfony - useful for Symfony projects
* validation of configuration methods that happens before running Rector itself

<br>

Are you curious about the implementation? In the end, [the secret is](https://github.com/rectorphp/rector-src/pull/2019) in a [single patched file](https://raw.githubusercontent.com/rectorphp/vendor-patches/main/patches/symfony-php-config-loader.patch) with 4 changed lines.

So what does it look like?

## Configuration as we Know It Now

```php
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\Core\Configuration\Option;
use Rector\DowngradePhp81\Rector\Property\DowngradeReadonlyPropertyRector;

// ups, possible conflict with ContainerConfigurator
return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    // too verbose params, constants and possible typo in param value
    $parameters->set(Option::PATHS, [[ // ups, "[[" typo
        __DIR__ . '/src/',
    ]]);

    $services = $containerConfigurator->services();
    $services->set(DowngradeReadonlyPropertyRector::class);
};
```

Such complexity is confusing just to read and easy to make error in.

## The new Way to Configure Rector

What if we could single class to configure the Rector?

```php
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->parallel();

    $rectorConfig->paths([
        __DIR__.'/src',
    ]);

    $rectorConfig->rule(DowngradeReadonlyPropertyRector::class);
};
```

## How to upgrade to `RectorConfig`?

First, be sure to use at least Rector 0.12.21. If your config has a few lines, you can handle it easily manually. But what if you have dozens of lines or even custom configs in your tests? Rector to the rescue!

```php
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        SetList::RECTOR_CONFIG
    ]);
};
```

<br>

Do you use Symfony? Avoid converting your Symfony `/configs` and only process only your Rector configs:

```bash
vendor/bin/rector process rector.php utils/rector/tests
```

Thanks to this upgrade set, we've [migrated all the Rector packages](https://github.com/rectorphp/rector-src/pull/2063/files) to use the new `RectorConfig` syntax.

## Configured Rules

Do you configure your rules? In previous version [we've added `configure()` method](/blog/new-in-rector-012-much-simpler-and-safer-rule-configuration) to help with validation of configuration:

```php
use Rector\Renaming\Rector\Name\RenameClassRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RenameClassRector::class)
        ->configure([
            'App\SomeOldClass' => 'App\SomeNewClass',
        ]);
```

<br>

But it was not enough, was it?

That's why now **you can use dedicated `ruleWithConfiguration()` method** with validated input:

```php
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'App\SomeOldClass' => 'App\SomeNewClass',
    ]);
```

<br>

Why not just use the `rule()` method with a magic 2nd argument?  The reason to separate these 2 methods is to give you fast feedback about wrong configuration:

* when you try to rule without configuration, e.g., `ChangeSwitchToMatchRector` with configuration, Rector will tell you the configuration is not needed
* and when you use a rule that requires configuration, e.g., `RenameClassRector`, you'll know about missing configuration too

<br>

Happy coding!
