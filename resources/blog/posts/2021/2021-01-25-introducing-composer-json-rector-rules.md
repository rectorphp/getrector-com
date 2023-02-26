---
id: 15
title: "Introducing composer.json Rector Rules"
perex: |
    In the last post we had a closer look at [Nette 3.1 changes in diffs](/blog/2021/01/18/smooth-upgrade-to-nette-31-in-diffs). That was the first upgrade with Rector ever, where you don't have to touch the `composer.json` file.
    <br>
    At all?

updated_since: '2022-04'
updated_message: |
    Since **Rector 0.12** a new `RectorConfig` is available with simpler and easier to use config methods.

deprecated_since: '2022-09'
deprecated_message: |
    This feature was never used apart the initial PR. The migration requires sensitive manual approach to the composer changes and such automation is not practical.
    <br><br>
    For these reasons, since **Rector 0.14.1** this was removed.
---

What is the first step you do if you want to upgrade a package? You change `composer.json` and update it. Easy, right?

<br>

## What Framework Upgrade means for `composer.json`?

This simple sentence can develop into **multi-step task**:

- What is the next version of this package?
- Is it a minor or major version change?
- Should I run Rector first or update `composer.json` first?
- What are the exact version of dependencies that support the next version of my favorite framework?

The last step can take a whole week of detailed detective work to figure out.

<br>

In 2012, when the Composer created, most frameworks could be updated with one line change:

```diff
 {
     "require": {
-         "nette/nette": "^1.0"
+         "nette/nette": "^2.0"
     }
 }
```

Then we run composer update, and we're done. Well, at least for the `composer.json` update.

<br>

How is it now? Today we are using monorepos, split packages, and small packages with narrow features. One package is handling dependency injection; one package is for forms, another for translations.

## What Packages and Versions we need for Nette 3.1?

Let's look at the [Nette 3.1 upgrade](/blog/2021/01/18/smooth-upgrade-to-nette-31-in-diffs). Nette package tagging is not standard monorepo-like, where 1 version is the same for all packages at a certain point in time.

Instead, Nette uses per-repository tagging. When `nette/application` is one version 3.1, the `nette/forms` can be `2.9`.

**How do we find out which packages are part of the upgrade for Nette "3.1"?**

Trial and error of careful looking into 20+ repositories in [Nette GitHub organization](http://github.com/nette/).
In the end, we end up with something like this:

```diff
 {
     "require": {
-        "nette/application": "^3.0",
+        "nette/application": "^3.1",
-        "nette/di": "^2.4",
+        "nette/di": "^3.0",
-        "nette/http": "^3.0",
+        "nette/http": "^3.1",
-        "nette/utils": "^3.0",
+        "nette/utils": "^3.2",
-        "latte/latte": "^2.3"
+        "latte/latte": "^2.9"
     }
 }
```

Oh, do you use 3rd party packages?

```diff
 {
     "require": {
-        "contributte/console": "^0.8",
+        "contributte/console": "^0.9",
-        "contributte/event-dispatcher": "^0.7",
+        "contributte/event-dispatcher": "^0.8",
-        "nettrine/annotations": "^0.5"
+        "nettrine/annotations": "^0.7"
     }
 }
```

That is so much work that every developer has to figure out over and over again. The combination of packages is different for every project, but the changed `composer.json` packages always have the same values.

<br>

That's when Lulco came to Rector repository with a question:

<blockquote class="blockquote text-center">
    "How can we automate this?"
</blockquote>

<br>

## Introducing Composer Rector

Many rules in Rector allow configuration via config. There you can set values in an array or value object. E.g., class rename:

```php
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Config\RectorConfig;

return function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'App\SomeOldClass' => 'App\SomeNewClass',
    ]);
};
```

<br>

What if something similar was possible for `composer.json`? Something we could configure to do typical work for us:

- upgrade this package to this version
- replace this package with another
- remove this package

Those Rector rules could be named like:

- `ChangePackageVersionComposerRector`
- `ReplacePackageAndVersionComposerRector`
- `RemovePackageComposerRector`

They would be easily configurable:

```php
use Rector\Composer\Rector\ChangePackageVersionComposerRector;
use Rector\Composer\Rector\RemovePackageComposerRector;
use Rector\Composer\ValueObject\PackageAndVersion;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
use Rector\Config\RectorConfig;

return function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ChangePackageVersionComposerRector::class, [
        new PackageAndVersion('nette/application', '^3.1'),
        new PackageAndVersion('nette/di', '^3.0'),
        new PackageAndVersion('nette/http', '^3.1'),
        new PackageAndVersion('nette/utils', '^3.2'),
        new PackageAndVersion('contributte/console', '^0.9'),
        new PackageAndVersion('nettrine/annotations', '^0.7'),
    ]);

    $rectorConfig->ruleWithConfiguration(RemovePackageComposerRector::class, [
        'nette/component-model', 'nette/neon',
    ]);
};
```

## Change only Found Packages

They also respect existing `composer.json`. E.g., if the `nette/di` is not there, it would not be added with a newer version but skipped.

In the end, you only run Rector and let it upgrade both your PHP code and your `composer.json`:

```bash
vendor/bin/rector process
```

<br>

## How does such Set Look for Nette 3.1?

Pretty neat. See `NETTE_31` set [for full setup](https://github.com/rectorphp/rector/blob/9c26ebf430a76d1dc4a65d3dc970705451c9b8fd/config/set/nette-31.php#L129-L162).

<br>

<br>

Happy coding!
