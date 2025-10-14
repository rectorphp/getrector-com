What is a PHP 8.3 project without a single type declaration?

A horse with Tesla bodywork.

<br>

Type coverage is one of the most influential metrics in a modern PHP project. We can have a high PHP version in `composer.json`, but our code can still be full of `mixed` types, giving us zero confidence. There is a [`type-coverage`](https://github.com/TomasVotruba/type-coverage) package that helps raise the bar 1 % at a time.

How can we use Rector to help out with type coverage?

**What happens if we add full set:**

```diff
 <?php

 use Rector\Config\RectorConfig;

 return RectorConfig::configure()
     ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
+    ->withPreparedSets(typeDeclarations: true);
```

Let's run Rector:

```bash
vendor/bin/rector --dry-run
```

Wow, over **90 % of files have changed**. That's going to be a very long review. We can do better than that.

## One Level at a Time

Instead of applying ~50 type declaration rules at once, we can apply them individually. This is much easier to review and explain to your team. But which one should we start with?

We took the liberty of sorting the rules from the easy-pick to more complex ones. You can enable them yourself and go one level at a time:

```diff
 <?php

 use Rector\Config\RectorConfig;

 return RectorConfig::configure()
     ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
-    ->withPreparedSets(typeDeclaration: true);
+    ->withTypeCoverageLevel(0);
```

Now run Rector to see the changed files:

```php
vendor/bin/rector
```

Only five files? We can do that in a day. We create a pull request, get a review, and merge. The next day, we can continue with level 1. You get the idea.

## Type Coverage and Type Coverage Docblocks Levels

Rector offer ruleset to fill known type declarations.

Since Rector 2.2, it also offers another ruleset to fill docblock `@param`, `@return` or `@var` types, where native type hints are not possible, e.g. `string[]`.

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withTypeCoverageLevel(0)
    ->withTypeCoverageDocblockLevel(0);
```

## Dead Code, Code Quality and Coding Style Levels

Are you done with the type level and reached [99 % type coverage](https://github.com/tomasVotruba/type-coverage)? It's time to move on to dead code removal and to improve code quality and coding style.

Again, we avoid full-blown prepared set, and make use of level methods:

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0)
    ->withCodingStyleLevel(0);
```

We increase it by 1, run Rector, create a pull request, get a review, and merge.

Once we reach the highest level, we can move on to [next prepared sets](/documentation/set-lists#content-prepared-sets).
