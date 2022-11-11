---
id: 19
title: "Legacy Refactoring made Easy with Static Reflection"
perex: |
    Properly configured class autoloading have been a big ~~requirement~~ problem for many projects that do not use flawless PSR-4 autoload. It took two months of hard work of our team and Rector community, but we're here.
    <br><br>
    What is a static reflection, and how can you use it?

since_rector: 0.10
---

## What is Static Reflection?

To perform static analysis of code in vendor, Rector needs to use reflection over the classes. The **class must be autoloaded so we can use reflection on it**. For classes, we can use PSR-4 or a class map to make that happen. Some projects don't use autoload at all as the framework handles it for them. Not only frameworks but also some tools have their autoloader - e.g., PHPUnit, so tests are in non-PSR-4 namespace structure too.

What about functions?

```php
function hi()
{
    echo 'hello';
}

hi();
```

To autoload functions, we had to use `include 'function_file.php`. Including this file causes PHP to run it and shows "hello" during `vendor/bin/rector` run.

Simple "hello" is ok, but what about connecting to the database? Removing files etc.? "I've just run a CLI tool a few files were removed" is not a problem we want to deal with.

<br>

So what is static reflection? Instead of including a file with the `hi()` function, we parse the file AST and **analyze the file without running it**. We can ask a particular service, `ReflectionProvider`, for the function - if it's autoloaded, we'll get a native reflection. If not, we'll get the AST-based reflection.

You can **read more** in a great post on PHPStan blog - [Zero Config Analysis with Static Reflection](https://phpstan.org/blog/zero-config-analysis-with-static-reflection).

<br>

## Configuration Everywhere

In Rector 0.9 and below, you could define files and directories to autoload - with a bit of [RobotLoader help](https://tomasvotruba.com/blog/2020/06/08/drop-robot-loader-and-let-composer-deal-with-autoloading/).

```php
// rector.php
$parameters->set(Option::AUTOLOAD_PATHS, [
    __DIR__ . '/project-without-composer',
]);
```

<br>

This was very frustrating as every PSR-4 incompatible file had to be included. Even simple single file run like these:

```bash
vendor/bin/rector process someFile.php
```

We had to include the file:

```bash
vendor/bin/rector process someFile.php --autoload-file someFile.php
```


<br>

## Rector Switch to Static Reflection

Rector is using PHPStan to analyse types for couple of years now. PHPStan implemented Static Reflection last summer. It was about time to give this feature to Rector users too:

<a href="https://github.com/rectorphp/rector/pull/5665">
    <img src="https://user-images.githubusercontent.com/924196/111977050-9a4f0f00-8b02-11eb-8923-8acdfe362dbf.png" class="img-thumbnail">
</a>

<br>

## How to Include Custom Autoload?

Static reflection now only parses the files found in `AUTOLOAD_PATHS`. That means those files are not executed and not included anymore. In some cases like custom autoload or files with defined constant, you still need to include them.

To keep this working, move these files to `BOOTSTRAP_FILES` parameter:

```diff
-$parameters->set(Option::AUTOLOAD_PATHS, [
+$parameters->set(Option::BOOTSTRAP_FILES, [
     __DIR__ . '/constants.php',
     __DIR__ . '/project/special/autoload.php',
 ]);
```

<br>

Do you want **to try it out**? This week the Rector 0.10 is released with static reflection on-board:

```bash
composer require rector/rector:^0.10 --dev

# or prefixed version
composer require rector/rector-prefixed:^0.10 --dev
```

<br>

## Independent Low Maintenance Tests

In the past, every single test fixture was included so Rector could analyse it. With a project with 3192 test fixture, that often lead to conflicts on same-named classes or functions. One of the positive externalities is that test fixtures don't have to be unique classes anymore. Every fixture file is loaded independently on another. Now contributing Rector became easier [with single click from demo](https://getrector.org/demo).

<br>

Happy coding!
