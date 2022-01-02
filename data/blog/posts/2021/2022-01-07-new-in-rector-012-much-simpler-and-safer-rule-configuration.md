---
id: 29
title: "New in Rector 0.12 - Much Simpler and Safer Rule Configuration"
perex: |
    Configurable rules are the most powerful building stone for instant upgrade sets. Do you want to upgrade from Symfony 5 to 6? You'll mostly deal with renamed classes, renamed methods, new default arguments in method or renamed class constants.
    <br><br>
    In the end we have to configure around 10 rule to get the most job done. That's why we focused on developer experience and added new `configure()` method in Rector 0.12.
contributor: TomasVotruba
pull_request_id: 1276
---

Each configurable Rector rule implements own `configure()` method.

To register rule in `rector.php`, we use [Symfony PHP configs](https://symfony.com/doc/current/service_container/configurators.html#using-the-configurator) syntax.

<br>

There we pass arguments that configure our rule, e.g. `RenameClassRector`:

```php
use Rector\Renaming\Rector\Name\RenameClassRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RenameClassRector::class)
        ->call('configure', [[
            RenameClassRector::OLD_TO_NEW_CLASSES => [
                'App\SomeOldClass' => 'App\SomeNewClass',
            ],
        ]]);
};
```

While we have full autocomplete support thanks to PHP, there are few downsides of this approach.

* we have to be careful about exact syntax of nested arrays
* we have to use class constants to pass the nested array to

<br>

What exactly does it mean? Well, any of following syntaxes would crash:

```php
->call('configure', [[[
    ...
]]]);
```

...or...

```php
->call('configure', [
    ...
]);
```

...or...

```php
->call('configure', [[
    [
        'App\SomeOldClass' => 'App\SomeNewClass',
    ],
]]);
```

<br>

We think, that's unnecessary complexity. Do you agree?

<br>

## New `configure()` Method to the Rescue

We don't like to make you think about implementation details **We know there are more important goals you want to achieve**.

That's why **we remove the complexity** with `configure()` method right in the `rector.php` config.

<br>

The goal is clear:

* no constants
* single method
* write only the configuration, nothing else
* Rector validates input for you


```php
use Rector\Renaming\Rector\Name\RenameClassRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RenameClassRector::class)
        ->configure([
            'App\SomeOldClass' => 'App\SomeNewClass',
        ]);
};
```

Nice and clear.

<br>

## Say Good Bye to Value Object Inlining

Symfony does not support value object for configuration. That's why we had to come up with [own value object inliner](/blog/2020/09/07/how-to-inline-value-object-in-symfony-php-config), when we wanted to use value objects for configuration. It looked like this:

```php
use Rector\Transform\Rector\FuncCall\FuncCallToStaticCallRector;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

$services->set(FuncCallToStaticCallRector::class)
    ->call('configure', [[
        FuncCallToStaticCallRector::FUNC_CALLS_TO_STATIC_CALLS => ValueObjectInliner::inline([
            new FuncCallToStaticCall('dump', 'Tracy\Debugger', 'dump'),
        ])
    ]]);
```

<br>

In Rector 0.12 we can use simple syntax:

```php
use Rector\Transform\Rector\FuncCall\FuncCallToStaticCallRector;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

$services->set(FuncCallToStaticCallRector::class)
    ->configure([
        new FuncCallToStaticCall('dump', 'Tracy\Debugger', 'dump'),
    ]);
```

<br>

## Bonus: Rector now Validates Your Input

...




