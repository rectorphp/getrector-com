Every new project is different. It depends on the PHP version, framework, libraries, coding style, level of PHPStan without baseline, type coverage or dead code coverage.

Rector runs natively on PHP 7.2 and higher, so [you can install it easily](/documentation). On PHP 7.1, use [the following setup](/documentation/how-to-run-on-php-53).

## 1. Take it Slow

The most crucial step is to integrate into a new project slowly. We apply [Rector rulesets in our clients' projects](/hire-team) carefully and knowingly. The goal is not to make a huge change fast but to get you and your teammates comfortable, trust changes in pull requests, and be able to review them.

What does it mean?

* don't apply all rules at once at first
* start with 1-3 rules that are easy to integrate and are safe
* make sure the 1st PR is merged; only then start adding new rules


## 2. Upgrade PHP First

Before diving into any prepared sets, we start with the crucial part - the PHP upgrade sets. Let's say this is a composer file of our project:

```json
{
    "require": {
        "php": "^7.4"
    }
}
```

This means our code base *can* use features from PHP 7.4, but it doesn't mean it actually uses them. So at first, we check what is the lowest version our codebase uses:

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
    ->withPhpSets();
```

This configuration tells Rector, "Upgrade my code to PHP 7.4, based on the composer.json version." Does that sound about right?

No, because this would invoke sets from PHP 5.3, 5.4, 5.5, 5.6, 7.0, 7.1, 7.2, 7.3 and 7.4 simultaneously. That's over 100 rules that will be applied. That sounds dangerous to do and even more tedious to review.

## 3. One PHP Set at a Time

Instead, we take one ruleset at a time:

```diff
 <?php

 use Rector\Config\RectorConfig;

 return RectorConfig::configure()
     ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
-    ->withPhpSets();
+    ->withPhpSets(php53: true);
```

Now, we told Rector, "Instead of applying all PHP sets up to 7.4, apply only sets up to 5.3. "

<br>

We run Rector to see the changes it proposes:

```bash
vendor/bin/rector --dry-run
```

If all looks good in the diff, we apply Rector:

```bash
vendor/bin/rector
```

Instead of 100s of rules, we only run a few of them. We can create a small pull request, **get a review the same day, and merge**.

<br>

The next day, we can continue with the following PHP version:

```diff
 <?php

 use Rector\Config\RectorConfig;

 return RectorConfig::configure()
     ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
-    ->withPhpSets(php53: true);
+    ->withPhpSets(php54: true);
```

Again, we run Rector, apply changes, create pull-request, and go for merge. Slowly but surely, **we are making our codebase better at a stable and safe pace**.

<br>

### Skip what you don't need

Every codebase is different, and sometimes, we come across a rule that is not safe to apply, or files we want to skip.
Use [ignoring](/documentation/ignoring-rules-or-paths) for that.

## Prepared sets, one level at a time

Rector provides dozens of [prepared sets](/documentation/set-lists). But the same way we don't read every book in library we visit for first time, we don't enable all prepared sets at once.

Instead, **use [level methods](/documentation/levels) and take it step by step**. It more relaxing path to reach the goal.
