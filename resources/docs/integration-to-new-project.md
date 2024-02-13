Every new project is different. It depends on the PHP version, framework, libraries, coding style, level of PHPStan without baseline, type coverage or dead code coverage.

Rector runs natively on PHP 7.2 and higher, so [you can install it easily](/documentation). On PHP 7.1, use [the following setup](/documentation/how-to-run-on-php-53).

## 1. Take it Slow

The most crucial step is to integrate into a new project slowly. We apply [Rector rulesets in our clients' projects](/hire-team) carefully and knowingly. The goal is not to make a huge change fast but to get you and your teammates comfortable, trust changes in pull requests, and be able to review them.

What does it mean?

* don't apply all rules at once at first
* start with 1-3 rules that are easy to integrate and are safe
* make sure the 1st PR merged; only then start adding new rules


## 2. Upgrade PHP First

Before diving into any prepared sets, we start with the crucial part - the PHP upgrade sets. Let's say this is a composer file of our project:

```json
{
    "require": {
        "php": "^7.4"
    }
}
```

This means our code base *can* use features from PHP 7.4. But it doesn't mean we it actually uses them. So at first, we check what is the lowest version our codebase uses:

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
    ->withPhpSets();
```

This configuration tells Rector, "Upgrade my code to PHP 7.4, based on the composer.json version. " Does that sound about right?

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

Every codebase is different, and sometimes, we come across a rule that is not safe to apply. We can skip it for now:

```diff
 <?php

 use Rector\Config\RectorConfig;
+use Rector\Php54\Rector\FuncCall\RemoveReferenceFromCallRector;

 return RectorConfig::configure()
     ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
+    ->withSkip([RemoveReferenceFromCallRector::class])
     ->withPhpSets(php54: true);
```

This is an entirely valid upgrade process. We will deal with skips later once our codebase is upgraded to our PHP version and much more robust.

Check [other ways to use `withSkip()`](/documentation/ignoring-rules-or-paths).

## 4. Type Coverage Level

What is a PHP 8.3 project without a single type declaration?

A horse with Tesla bodywork.

<br>

Type coverage is one of the most influential metrics in a modern PHP project. We can have a high PHP version in `composer.json`, but our code can still be full of `mixed` types, giving us zero confidence. There is a PHPStan package - [`type-coverage`](https://github.com/TomasVotruba/type-coverage)- that helps raise the bar one 1 % at a time.

How can we use Rector to help out with type coverage? We can add a prepared set:

```diff
 <?php

 use Rector\Config\RectorConfig;

 return RectorConfig::configure()
     ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
+    ->withPreparedSets(typeDeclaration: true);
```

Let's run Rector:

```bash
vendor/bin/rector --dry-run
```

Wow, over 90 % of files were changed. That's going to be a very long review. We can do better than that.

## 5. One Level at a Time

Instead of applying ~50 type declaration rules at once, we can apply them individually. This is much easier to review and explain to your team. But which one should we start with?

We took the liberty of sorting the rules from the easy-pick to more complex ones. You can enable them yourself and go one level at a time:

```diff
 <?php

 use Rector\Config\RectorConfig;

 return RectorConfig::configure()
     ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
-    ->withPreparedSets(typeDeclaration: true);
+    ->withTypeCoverageLevel(1);
```

Now run Rector to see the changed files:

```php
vendor/bin/rector
```

Only five files? We can do that in a day. We create a pull request, get a review, and merge. The next day, we can continue with level 2. You get the idea.

## 6. Dead Code Level

Are you done with the type level and reached [99 % type coverage](https://github.com/tomasVotruba/type-coverage)? It's time to move on to dead code removal.

Again, we could use the prepared dead-code set, but the number of changes would be huge. Instead, we make use of the dead-code level:

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
    ->withTypeCoverageLevel(40)
    ->withDeadCodeLevel(1);
```


We increase it by 1, run Rector, create a pull request, get a review, and merge.

Once we reach the highest dead code level, we can move on to [next prepared sets](/documentation/set-lists).
