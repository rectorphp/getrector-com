---
id: 55
title: "5 Common Mistakes in Rector Config and How to Avoid Them"
perex: |
    Rector is becoming a standard tool to automate PHP/package upgrades and code quality improvements. Last month, we crossed 60 000 downloads a day.

    Past 2 months, we've also improved CPU and memory performance, making Rector a lighter version.

    Yet, even fast and lightweight Rector can get stuck on simple config mistakes. We'll talk about the 5 most common ones and how to avoid them.
---

We used the following tips when [upgrading the Rector config in Mautic](https://github.com/mautic/mautic/pull/12676).

We want to share them with you so you can get the most out of Rector.

<br>

## 1. Use explicit paths over `/vendor`

This happens very rarely, but it's worth mentioning. Rector should always run only on the code you own**. If you run it in the root directory, the memory might bloat on the bare `/vendor` directory.

```php
$rectorConfig->paths([
    __DIR__ . '/../first-project',
    __DIR__ . '/../second-project',
]);
```

* Be sure to **install Rector directly to your project** using composer, like any other dev package, to avoid such accidents on climbing paths up.

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

This mistake is hard to spot but effective in throttling your Rector run. Doctrine migrations, test fixtures, and **any other generated generated PHP files should be excluded**. Why? At the start, you can have 5-10 database migrations - that's fine, but the older the project is, the greater the burden to handle on every run.

We've seen projects with 200-600 migration files carefully hidden in `/src/Migrations` like any other production file.
These files should be excluded not only for performance gains but also to make sure their structure and behavior will persist.

```php
$rectorConfig->skip([
    __DIR__ '/app/Migrations',
]);
```

Moving those files into the root `/migrations` directory is even better, so Rector and other tools like PHPStan and ECS do not check them.

<br>

## 3. Avoid keeping `UP_TO_*` for longer than needed

Rector can handle both code improvements and package upgrades. The upgrade is usually a one-time job to get your codebase to the latest PHP and packages. You can find the following sets in your code:

```php
$rectorConfig->sets([
    PHPUnitLevelSetList::UP_TO_PHPUNIT_100,
    SymfonyLevelSetList::UP_TO_SYMFONY_63,
    LevelSetList::UP_TO_PHP_81,
]);
```

These are what we call **low-hit sets**. They contain dozens of rules that will never find any code to upgrade or change, yet they're still run on every Rector run. That's like checking every release of The Time magazine for prime numbers higher than 1,000,000 - it's a waste of your time and resources.

For, the Symfony 6.3 level set itself contains 80 rules - including a rule that renames class and checks class renames in a nested manner.

### How should we use these `UP_TO_*` sets then?

**Enable them just once during the upgrade period**. Let's say we are upgrading to Symfony 6.3 - we keep the set in `rector.php` for the time being, and once we're on Symfony 6.3, we remove it.

Don't worry; any leftovers will be reported again in 6 months when you handle the Symfony 6.4/7 upgrade.

<br>

## 4. Instead of long `rules()` calls, use slim `sets()`

During the upgrade period, it's also typical to add one rule at a time, run Rector, and push the fixed cases. Then repeat.
That way, you might end up with 100+ rules listed one by one from a single set:

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

This makes `rector.php` hard to read and maintain. Instead, use the `sets()` method with the whole set to keep it slim:

```php
use Rector\Set\ValueObject\SetList;

$rectorConfig->sets([
    SetList::DEAD_CODE,
]);
```

In case there are rules you don't like from a particular set, [skip them using `skip()`](https://getrector.com/documentation/ignoring-rules-or-paths).

<br>

## 5. Make use of code quality sets

Last but not least, make sure you're using the most powerful feature of Rector. It's not the upgrade sets but the refactoring sets.

Those sets are the opposite of the low-hit sets discussed in point 3. **They're helping you with everyday coding** - in PHP 7.0, PHP 8.2, Laravel, Symfony, or in plain PHP.

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

Avoid adding them all at once, as such a PR is impossible to review. Instead, add one set by another and push the fixes in between.

<br>

Happy coding!
