In real world, "jack" is a tool that helps to raise your heavy car one inch at a time. So you can fix any issues down there and drive safely on journeys to come.

In Composer world, Jack helps you to raise dependencies one version at a time, safely and steadily.

<br>

## Install

Rector Jack is downgraded and scoped. It requires **PHP 7.2+** and can be installed on any legacy project.

Install with Composer:

```bash
composer require rector/jack --dev
```

## Features

### 1. Too many outdated dependencies? Let your CI tell you

This command checks **major outdated dependencies** in your project. If there is **more than 5**, it will fail:

```bash
vendor/bin/jack breakpoint
```

Use `--limit` option to change the number of allowed major outdated dependencies.

<br>

Use this command in CI to prevent too many outdated dependencies. It helps us to keep the project healthy without constant need to checking the dependencies yourself.

<br>

### 2. Open up Next versions

We know we're behind the latest versions of our dependencies, but where to start? Which versions should be force to update first? We can get lot of conflicts if we try to bump wrong end of knot.

Instead, let Composer handle it. How? We open-up package constraints in `composer.json`:

```bash
vendor/bin/jack open-versions
```

This command opens up 5 versions to their next nearest step, e.g.:

```diff
 {
     "require": {
         "php": "^7.4",
-            "symfony/console": "5.1.*"
+            "symfony/console": "5.2.*|5.2.*"
         },
         "require-dev": {
-            "phpunit/phpunit": "^9.0"
+            "phpunit/phpunit": "^9.0|^10.0"
         }
     }
 }
```

Then we run Composer to do the work:

```bash
composer update
```

You can change number of opened versions with `--limit` option. Run with `--dry-run` to see the proposed changes first.

<br>

See [Jack Github repository](https://github.com/rectorphp/jack) for full documentation.
