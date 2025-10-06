---
id: 80
title: "Rector 2.2: New rules for Array Docblocks"
perex: |
    As you know, we provide an [upgrade services](https://getrector.com/hire-team) to speed up the modernization of codebases. Part of this service is getting PHPStan to level 8 with no baseline (only edge cases).

    Level 6 is known for requesting more detailed types over `array`,
      `iterable` or `Iterator` type hints. Bare `mixed` or `array` should be replaced with explicit key/value types, e.g., `string[]` or `array<int, SomeObject>`.

    At first, we did this work manually. Later, we made custom Rector rules that we kept private.

    Today, we are open-sourcing these rules to help you with the same task.
---

<div class="alert alert-warning mt-3 mb-5">
This feature is experimental. We look for your feedback in real projects. Found a glitch, or do you expect a different output? Let us known <a href="https://github.com/rectorphp/rector/issues">on GitHub</a>.
</div>

We designed these rules to avoid filling useless types like `mixed`, `mixed[]`, or `array`. If the Rector doesn't know better, it will skip these cases. We want to fill those types that humans would do to improve code readability and static analysis.

<br>

Let's look at a few examples that are missing detailed types and that Rector can improve now:

## 1. Known Return Scalar

```php
function getNames(): array
{
    return ['John', 'Jane'];
}
```

<br>

Now this is straightforward; it can be improved to:

```diff
+/**
+ * @return string[]
+ */
 function getNames(): array
 {
     // ...
 }
```

Why do this manually in 100s of places, if Rector can do it for you?

<br>

## 2. Known Return Objects

Let's look at another example:

```php
function getUsers(): array
{
    $user = [];

    $users[] = new User('John');
    $users[] = new User('Jane');

    return $users;
}
```

<br>

No-brainer:

```diff
+/**
+ * @return User[]
+ */
 function getUsers(): array
 {
     // ...
 }
```

<br>

## 3. Known Shared Object Type

What if there are multiple different objects that all share a single contract interface?

```php
final class ExtensionProvider
{
    public function provide(): array
    {
        return [
            new FirstExtension(),
            new SecondExtension(),
        ];
    }
}
```

<br>


In a real project, we would have to open all of those classes, check parent classes and interfaces, and try to find the first common one. Now we don't have to, Rector does it for us:

```diff
+    /**
+     * @return ExtensionInterface[]
+     */
     public function provide(): array
     {
         // ...
     }
```

<br>

## 4. Known `array_map()` return

We can infer the type from functions like `array_map()`:

```diff
+/**
+ * @return string[]
+ */
 public function getNames(array $users): array
 {
     return array_map(fn (User $user): string => $user->getName(), $users);
 }
```

<br>

## 5. Known private method types

What if the method is private and is called only in a local class? We can now collect all the method calls and learn their type:

```diff
 final class IncomeCalculator
 {
     public function addCompanyTips(): void
     {
        $this->addTips([100, 200, 300]);
     }

     public function addPersonalTips(): void
     {
         $this->addTips([50, 150]);
     }

+    /**
+     * @param int[] $tips
+     */
     private function addTips(array $tips): void
     {
     }
 }
```

...and many more. Right now, the initial set contains **[15 rules](https://github.com/rectorphp/rector-src/blob/main/src/Config/Level/TypeDeclarationDocblocksLevel.php)**, and we plan to extend it further. Got an idea for an obvious rule that you keep doing manually and is not covered yet? Let us know.

<br>

## Smart Override

Rector is smart enough to keep detailed types, but override those dummy ones:

```diff
 /**
- * @return mixed[]
+ * @return string[]
  */
 function getNames(): array
 {
    return ['one', 'two']
 }
```

<br>

## Start with Levels

The best way to start using this set is via [level feature](/documentation/levels). Add this single line to your `rector.php` config:

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withTypeCoverageDocblockLevel(0);
```

<br>

And take it one level at a time:

```diff
 <?php

 use Rector\Config\RectorConfig;

 return RectorConfig::configure()
-    ->withTypeCoverageDocblockLevel(0);
+    ->withTypeCoverageDocblockLevel(1);
```

<br>

In a rush or feeling lucky? Add full set:

```php
<?php

 use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPreparedSets(typeDeclarationDocblocks: true);
```

<br>

We've put a lot of work into making rules balanced and reliable, but it's still in the early testing phase. Give it a go, let us know how slim your baseline got after a single Rector run.

<br>

Happy coding!
