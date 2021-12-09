---
id: 5
title: "How to Inline Value Object in Symfony PHP Config"
perex: |
    Rector uses [PHP Symfony configs](/blog/2020/08/31/rector-is-moving-from-yaml-to-php-configs-what-changes-and-how-to-get-ready) for [many good reasons](https://tomasvotruba.com/blog/2020/07/16/10-cool-features-you-get-after-switching-from-yaml-to-php-configs/).
    <br><br>
    One of them is the possibility to have control over complex configurations with value objects.
    Would you like such features in your configs too? Unfortunately, Symfony does not support it out of the box.
    <br><br>
    What can we do about it?
---

## Why are Value Objects in Configuration Priceless?

Let's say we want to rename `dump()` func call to `Tracy\Debugger::dump()`.

```diff
-dump($value);
+Tracy\Debugger::dump($value);
```

KISS. Why not use a simple array?

Look at our [previous post](/blog/2020/08/31/rector-is-moving-from-yaml-to-php-configs-what-changes-and-how-to-get-ready#value-objects-configuration-ftw) about why the road to hell is paved with good intentions.

<br>

Now that we know why and when we want to use value objects, the question is *How can we use them in Symfony PHP Config*?

## 1. Direct Object

First obvious idea is to just use is like we would in PHP:

```php
// very dummy PHP approach
$funcCallToStaticCallRector = new FuncCallToStaticCallRector();

$configuration = [
    FuncCallToStaticCallRector::FUNC_CALLS_TO_STATIC_CALLS => [
         new FuncCallToStaticCall('dump', 'Tracy\Debugger', 'dump'),
    ]
];

$funcCallToStaticCallRector->configure($configuration);
```

This should work, right? It's just passing a bunch of scalar values:

```php
// rector.php

$services->set(FuncCallToStaticCallRector::class)
    ->call('configure', [[
        FuncCallToStaticCallRector::FUNC_CALLS_TO_STATIC_CALLS => [
            new FuncCallToStaticCall('dump', 'Tracy\Debugger', 'dump'),
        ]
    ]]);
```

↓

```bash
 [ERROR] Cannot use values of type
         "Rector\Transform\ValueObject\FuncCallToStaticCall" in service
         configuration files.
```

<br>

<em class="fas fa-fw fa-times text-danger fa-2x"></em>

## 2. Native `inline_service()` Function

Fortunately, Symfony gives us a little trick. The `inline_service()` basically registers a local service, just for the configuration:

```php
// rector.php

$services->set(FuncCallToStaticCallRector::class)
    ->call('configure', [[
        FuncCallToStaticCallRector::FUNC_CALLS_TO_STATIC_CALLS => [
            inline_service(FuncCallToStaticCall::class)
                ->args(['dump', 'Tracy\Debugger', 'dump']),
        ]
    ]]);
```

This works!

There is just little detail. The IDE autocomplete we want from value objects:

<img src="/assets/images/blog/2020/inline_service_dead_value_object.gif" class="img-thumbnail">

...is gone.

- How can we know there are precisely 3 arguments?
- What is their order?
- What is their name?
- When does `__construct()` type validation happens?

This approach shuts us back to coding in the dark with Notepad.

<br>

<em class="fas fa-fw fa-times text-danger fa-2x"></em>

## 3. Best of Both Worlds - `inline_value_objects()`

What we need here?

- A value object configuration that looks like a value object:

    ```php
    new SomeClass('value');
    ```

- 1 line solution
- Ideally, in Symfony way, so it the least surprise to use

<br>

To cover all benefits together, we've create **custom `Symplify\SymfonyPhpConfig\ValueObjectInliner` class**:

```php
// rector.php
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

$services->set(FuncCallToStaticCallRector::class)
    ->call('configure', [[
        FuncCallToStaticCallRector::FUNC_CALLS_TO_STATIC_CALLS => ValueObjectInliner::inline([
            new FuncCallToStaticCall('dump', 'Tracy\Debugger', 'dump'),
            // it handles multiple items without duplicated call
            new FuncCallToStaticCall('d', 'Tracy\Debugger', 'dump'),
            new FuncCallToStaticCall('dd', 'Tracy\Debugger', 'dump'),
        ])
    ]]);
```

Do you want to sure it too?

```bash
composer require symplify/symfony-php-config
```

## 4. Simple `configure()` Method

Since Rector 0.12 you can use `->configure()` method, that handles the complexity for you:

```php
$services->set(FuncCallToStaticCallRector::class)
    ->configure([
        new FuncCallToStaticCall('dump', 'Tracy\Debugger', 'dump'),
        // it handles multiple items without duplicated call
        new FuncCallToStaticCall('d', 'Tracy\Debugger', 'dump'),
        new FuncCallToStaticCall('dd', 'Tracy\Debugger', 'dump'),
    ]);
```

Instead of complexity keys, constants and inliners, just pass it array of value objects.

And you're set!

<br>

<em class="fas fa-fw fa-check text-success fa-2x"></em>

This way, **anyone can configure even the most complex Rector rules** without ever looking inside the Rector rule and scan for configuration values.

## And What Rector uses What Value Object?

In convention over configuration principle, all you need to know **the rule name**.

Could you guess the value object naming pattern?

- `FuncCallToStaticCallRector` (rule)
- `FuncCallToStaticCall` (value object)

That's it!

<br>

Happy and safe coding!
