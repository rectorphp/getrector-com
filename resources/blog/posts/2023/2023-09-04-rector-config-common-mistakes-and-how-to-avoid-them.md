---
id: 55
title: "Rector Config Common Mistakes and How to Avoid Them"
perex: |
    Rector is becoming standard tool to automate PHP/package upgrades and code quality improvements. Last month crossing nearly 60 000 downloads a day.

    Past 2 months we've also improved CPU and memory performnace, making Rector lighter version ever.

    Yet, even fast and lighweight Rector can get stuck on simple config mistakes. We'll talk about the 5 most common ones and how to avoid them.
---

We used following tips when [upgrading Rector config in Mautic](https://github.com/mautic/mautic/pull/12676). We want to share them with you so you can get the most out of Rector too.

<br>

## 1. Use explicit paths over `/vendor`

This happens very rarely, but it's worth mentioning. Rector should always **run only on the code you own**. If you run it the root directory, the memory might bloat on bare `/vendor` directory.

```php
$rectorConfig->paths([
    __DIR__ . '/../first-project',
    __DIR__ . '/../second-project',
]);
```

* Be sure to **install Rector directly to your project** using composer like any other dev package, to avoid such accidents on climbing paths up.

* Make sure you use the paths you own - including tests and config directory:

```php
$rectorConfig->paths([
    __DIR__ . '/config',
    __DIR__ . '/src',
    __DIR__ . '/tests',
]);
```

<br>

## 2. Avoid checking migrations and test fixtures

This mistake is hard to spot, but effective in throttling your Rector run. Doctrine migrations, test fixture and **any other generated generated PHP files should be excluded**. Why? At start you can have 5-10 database migrations - that's fine, but the older project is, the greater the burden to handle on ever run.

We've seen projects with 200-600 migration migration files, carefully hidden in `/src/Migrations` like any other production file.
These files should be excluded not only for performance gains, but also to make sure their structure and behavior will persist.

```php
$rectorConfig->skip([
    __DIR__ '/app/Migrations',
]);
```

Even better is to move those files into root `/migrations` directory, so they are not checked by Rector and other tools like PHPStan and ECS at all.

<br>

## 3. Avoid keeping `UP_TO_*` for longer than needed

Rector can handle both code improvements and package upgrades. The upgrade is usually one-time job to get your codebase to latest PHP and packages. You can find following sets in your code:

```php
$rectorConfig->sets([
    PHPUnitLevelSetList::UP_TO_PHPUNIT_100,
    SymfonyLevelSetList::UP_TO_SYMFONY_63,
    LevelSetList::UP_TO_PHP_81,
]);
```

These are what we call **low-hit sets**. They contains dozens rule that will never find any code to upgrade or change, yet they're still run on every Rector run. That's like checking every release of The Time magazine for prime numbers higher than 1 000 000 - it's waste of your time and resources.

E.g. the Symfony 6.3 level set itself contains 80 rules - including rule that renames class and is checking class renames in nested manner.

### How should we use these `UP_TO_*` sets then?

**Enable them just once during the upgrade period**. Let's say we are upgrading to Symfony 6.3 - we keep the set in `rector.php` for time being and once we're on Symfony 6.3, we remove it.

Don't worry, any leftovers will be reported again in 6 months when you'll handle Symfony 6.4/7 upgrade.

<br>

## 4. Instead of long `rules()` calls, use slim `sets()`

During the upgrade period, it's also typical to add one rule at a time, run Rector and push the fixed cases. Then repeat.
That way you might end up with 100+ rules listed one by one from single set:

```php
$rectorConfig->rules(
    \Rector\DeadCode\Rector\BooleanAnd\RemoveAndTrueRector::class,
    \Rector\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector::class,
    \Rector\DeadCode\Rector\ClassConst\RemoveUnusedPrivateClassConstantRector::class,
    \Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector::class,
    \Rector\DeadCode\Rector\Concat\RemoveConcatAutocastRector::class,
    \Rector\DeadCode\Rector\Return_\RemoveDeadConditionAboveReturnRector::class,
    \Rector\DeadCode\Rector\For_\RemoveDeadContinueRector::class,
    \Rector\DeadCode\Rector\For_\RemoveDeadIfForeachForRector::class,
    \Rector\DeadCode\Rector\If_\RemoveDeadInstanceOfRector::class,
]);
```

This makes `rector.php` hard to read and maintain. Instead, use `sets()` method with whole set to keep it slim:

```php
use Rector\Set\ValueObject\SetList;

$rectorConfig->sets([
    SetList::DEAD_CODE,
]);
```

In case there are rules you don't really like from particular set, just [skip them using `skip()`](https://getrector.com/documentation/ignoring-rules-or-paths).

<br>

## 5. Make use of code quality sets

Last but not least, make sure you're using the most powerful feature of Rector. It's not the upgrade sets, but refactoring sets.

Those sets are the opposite of low-hit sets we talked in point 3. **They're helping with you with everyday coding** - in PHP 7.0, PHP 8.2, in Laravel, in Symfony or in plain PHP.

You can find them in `SetList`:

```php
use Rector\Set\ValueObject\SetList;

$rectorConfig->sets([
    SetList::DEAD_CODE,
    SetList::CODE_QUALITY,
    SetList::CODING_STYLE,
    SetList::NAMING,
    SetList::TYPE_DECLARATION,
    SetList::PRIVATIZATION,
    SetList::EARLY_RETURN,
    SetList::TYPE_DECLARATION,
    SetList::INSTANCEOF,
]);
```

Avoid adding them all at once, as such PR is impossible to review. Instead add one set by another, and push the fixes in between.

<br>

Happy coding!
